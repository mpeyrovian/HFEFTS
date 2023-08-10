
ALTER TABLE `mydb`.`employment`
    ADD CONSTRAINT `CHK_employment_date_range`
        CHECK (`endDate` >= `startDate`);

ALTER TABLE `mydb`.`schedule`
    ADD CONSTRAINT `CHK_schedule_date_range`
        CHECK (`endTime` >= `startTime`);

