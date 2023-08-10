DELIMITER //
CREATE TRIGGER tr_schedule_date_range_insert
    BEFORE insert on `schedule`
    FOR EACH ROW
BEGIN
    IF NOT EXISTS (
        SELECT *
        FROM `employment` e
        WHERE e.id = NEW.employmentId
        AND case when e.endDate is not null  then NEW.date BETWEEN e.startDate AND e.endDate else NEW.date>=e.startDate end
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Scheduled date is outside of employment date range.';
END IF;
END //
DELIMITER ;



DELIMITER //
CREATE TRIGGER `tr_check_schedule_conflicts_insert`
    BEFORE insert on `schedule`
    FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1 FROM `schedule` s
        WHERE s.`employmentId` = NEW.`employmentId`
        AND s.`isCancelled` = 0
        AND s.`id` <> NEW.`id`
        AND (
            (s.`date` = NEW.`date` AND s.`endTime` > NEW.`startTime` AND s.`startTime` < NEW.`endTime`)
            OR (
                (SELECT `facilityId` FROM `employment` WHERE `id` = s.`employmentId`) =
                (SELECT `facilityId` FROM `employment` WHERE `id` = NEW.`employmentId`)
                AND s.`date` = NEW.`date` AND s.`startTime` = NEW.`startTime` AND s.`endTime` = NEW.`endTime`
            )
        )
    ) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Employee already scheduled at conflicting time or facility';
END IF;
END//
DELIMITER ;


DELIMITER //
create TRIGGER tr_schedule_time_gap_exists_insert
    BEFORE INSERT ON schedule
    FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1
        FROM schedule s
        WHERE s.employmentId = NEW.employmentId
          AND s.date = NEW.date
          AND ABS(TIME_TO_SEC(TIMEDIFF(NEW.startTime, s.endTime))) < 3600
          OR ABS(TIME_TO_SEC(TIMEDIFF(s.endTime, NEW.startTime))) < 3600
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Employee cannot be scheduled for two different periods on the same day without at least one hour in between.';
END IF;
END//
DELIMITER ;













DELIMITER //
CREATE TRIGGER `send_schedule_email_insert`
    AFTER insert on `schedule`
    FOR EACH ROW
BEGIN
    IF DAYOFWEEK(NEW.date) = 1 THEN
        INSERT INTO `email_sent` (`date`, `facility_id`, `sender`, `receirver`, `subject`, `body`)
    SELECT NOW(), e.`facilityId`, 'system', emp.`email`, 'Schedule for the coming week', CONCAT('Hello ', emp.`firstName`, ',\n\nHere is your schedule for the coming week:\n\n', GROUP_CONCAT(DISTINCT CONCAT(DATE_FORMAT(s.`date`, '%a %m/%d/%Y'), ' from ', TIME_FORMAT(s.`startTime`, '%h:%i %p'), ' to ', TIME_FORMAT(s.`endTime`, '%h:%i %p')) SEPARATOR '\n'), '\n\nThank you!') AS email_body
    FROM `schedule` s,`employment` e ,`employee` emp
    WHERE s.`employmentId` = e.id
      AND   e.`employeeId` = emp.id
      AND	  WEEK(s.`date`) = WEEK(NOW()) + 1
      AND s.isCancelled=0
    GROUP BY e.`facilityId`, emp.`id`;
END IF;
END//
DELIMITER ;


DELIMITER //
CREATE TRIGGER `covid_schedule_check_insert` BEFORE insert on `schedule`
    FOR EACH ROW
BEGIN
    SELECT COUNT(*) INTO @isInfected FROM infection
    WHERE employeeId = NEW.employmentId AND infection.infectionTypeId = 1
      AND date >= DATE_SUB(CURDATE(), INTERVAL 14 DAY);

    IF @isInfected > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot schedule. Employee is infected with COVID-19.';
END IF;
END//
DELIMITER ;




DELIMITER //
CREATE TRIGGER check_capacity_insert
    BEFORE insert on `employment`
    FOR EACH ROW
BEGIN
    IF (SELECT COUNT(*) FROM `employment` WHERE `facilityId` = NEW.`facilityId`) >= (SELECT `capacity` FROM `facility` WHERE `id` = NEW.`facilityId`) THEN
    SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'The number of employees working in this facility exceeds the facility’s capacity.';
END IF;
END//
DELIMITER ;



-- 20 -- if a doctor or a nurse gets infected by COVID-19, then the system should automatically cancel all the assignments for the infected employee for two weeks from the date of infection
DELIMITER //
CREATE TRIGGER `employee_infected_cancel_schedule_insert`
    AFTER insert on `infection`
    FOR EACH ROW
BEGIN
    SET @infection_type_id_for_covid = (SELECT id from infection_type WHERE type='COVID-19');
    IF NEW.infectionTypeId = @infection_type_id_for_covid THEN
        create temporary table if not exists tempTableScheduleIdsToBeCancelled
    SELECT schedule.id
    FROM schedule, employment, employee, employee_role
    WHERE schedule.employmentId = employment.id
      AND employment.employeeId = employee.id
      AND employee.roleId = employee_role.id
      AND (role = 'Doctor' OR role = 'Nurse')
      AND employment.employeeId = NEW.employeeId
      AND date < DATE_ADD(NEW.date, INTERVAL 2 WEEK)
      AND date > NEW.date;

    UPDATE schedule
    SET isCancelled='1'
    WHERE id IN (SELECT id FROM tempTableScheduleIdsToBeCancelled);

END IF;
END //
DELIMITER ;