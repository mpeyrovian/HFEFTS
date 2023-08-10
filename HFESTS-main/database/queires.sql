
-- -----------------------------------------------------
-- Table `mydb`.`employee_role`
-- -----------------------------------------------------
INSERT INTO `mydb`.`employee_role` (`role`)
VALUES
    ('Nurse'),
    ('Doctor'),
    ('Cashier'),
    ('Pharmacist'),
    ('Receptionist'),
    ('Administrative Personnel'),
    ('Security Personnel'),
    ('Regular Employee');

-- -----------------------------------------------------
-- Table `mydb`.`city`
-- -----------------------------------------------------
INSERT INTO `mydb`.`city` (`city`, `province`)
VALUES
    ('Toronto', 'Ontario'),
    ('Montreal', 'Quebec'),
    ('Vancouver', 'British Columbia'),
    ('Calgary', 'Alberta'),
    ('Ottawa', 'Ontario'),
    ('Edmonton', 'Alberta'),
    ('Winnipeg', 'Manitoba'),
    ('Quebec City', 'Quebec'),
    ('Hamilton', 'Ontario'),
    ('Halifax', 'Nova Scotia');

-- -----------------------------------------------------
-- Table `mydb`.`postal_code`
-- -----------------------------------------------------
INSERT INTO `mydb`.`postal_code` (`postalCode`, `cityId`)
VALUES
    ('A1A 1A1', 1),
    ('B2B 2B2', 2),
    ('C3C 3C3', 3),
    ('D4D 4D4', 4),
    ('E5E 5E5', 5),
    ('F6F 6F6', 6),
    ('G7G 7G7', 7),
    ('H8H 8H8', 8),
    ('I9I 9I9', 9),
    ('J1J 1J1', 10);


-- -----------------------------------------------------
-- Table `mydb`.`address`
-- -----------------------------------------------------
INSERT INTO `mydb`.`address` (`address`, `postalCodeId`)
VALUES
    ('123 Main St', 1),
    ('456 Oak St', 2),
    ('789 Maple Ave', 3),
    ('1011 Elm Blvd', 4),
    ('1213 Cedar Rd', 5),
    ('1415 Pine Ln', 6),
    ('1617 Birch Dr', 7),
    ('1819 Spruce Ct', 8),
    ('2021 Laurel Way', 9),
    ('2223 Poplar Trail', 10);

-- -----------------------------------------------------
-- Table `mydb`.`employee`
-- -----------------------------------------------------
INSERT INTO `mydb`.`employee` (`firstName`, `lastName`, `dateOfBirth`, `medicareCardNo`, `phone`, `addressId`, `citizenship`, `email`, `roleId`)
VALUES
    ('Rupert','Kennedy',Date('1967-12-25'),'312821','850-258-8223','1','CA','Rupert.Kennedy@gmail.com', 1),
    ('Joanna','Pena',Date('1969-02-11'),'231234','617-725-7353','2','IR','Joanna.Pena@gmail.com', 2),
    ('Erica','Thornton',Date('1971-12-17'),'544355','503-553-5841','3','CA','Erica.Thornton@gmail.com', 3),
    ('Tony','Krueger',Date('1972-11-14'),'342345','947-876-8543','4','US','Tony.Krueger@gmail.com', 4),
    ('Theodore','Dunlap',Date('1980-08-07'),'235423','432-548-1545','5','US','Theodore.Dunlap@gmail.com', 5),
    ('Juliet','Ayers',Date('1981-09-03'),'213423','312-977-6814','6','US','Juliet.Ayers@gmail.com', 1),
    ('Andreas','Orr',Date('1990-05-09'),'534543','312-875-6815','7','UK','Andreas.Orr@gmail.com', 2),
    ('Wyatt','Connor',Date('1996-08-13'),'346344','206-239-6802','8','CA','Wyatt.Connor@gmail.com', 3),
    ('Helena','James',Date('1999-07-20'),'453524','618-467-3821','9','CA','Helena.James@gmail.com', 4),
    ('Wayne','Goodwi',Date('1999-08-25'),'423423','314-953-3599','10','CA','Wayne.Goodwi@gmail.com', 5),
    ('John','Doe1',Date('1999-10-25'),'427523','354-753-3510','8','US','John.Doe1@gmail.com', 6),
    ('John','Doe2',Date('1999-10-25'),'427524','354-753-3511','8','US','John.Doe2@gmail.com', 6),
    ('John','Doe3',Date('1999-10-25'),'427525','354-753-3512','8','US','John.Doe3@gmail.com', 6),
    ('John','Doe4',Date('1999-10-25'),'427526','354-753-3513','8','US','John.Doe4@gmail.com', 6),
    ('John','Doe5',Date('1999-10-25'),'427527','354-753-3514','8','US','John.Doe5@gmail.com', 6),
    ('John','Doe6',Date('1999-10-25'),'427528','354-753-3515','8','US','John.Doe6@gmail.com', 6),
    ('John','Doe7',Date('1999-10-25'),'427529','354-753-3516','8','US','John.Doe7@gmail.com', 6),
    ('John','Doe8',Date('1999-10-25'),'427510','354-753-3517','8','US','John.Doe8@gmail.com', 6),
    ('John','Doe9',Date('1999-10-25'),'427511','354-753-3518','8','US','John.Doe9@gmail.com', 6),
    ('John','Doe10',Date('1999-10-25'),'428513','354-753-3519','8','US','John.Doe10@gmail.com', 6),

    ('Jane','Doe',Date('1998-10-25'),'475423','310-953-3899','2','CA','Jane.Doe@gmail.com', 1);


-- -----------------------------------------------------
-- Table `mydb`.`facility_type`
-- -----------------------------------------------------
INSERT INTO `mydb`.`facility_type` (`type`)
VALUES
    ('Hospital'),
    ('CLSC'),
    ('Clinic'),
    ('Pharmacy'),
    ('Special Installment');


-- -----------------------------------------------------
-- Table `mydb`.`facility`
-- -----------------------------------------------------
INSERT INTO `mydb`.`facility` (`name`, `addressId`, `phone`, `webAddress`, `typeId`, `capacity`,`generalManagerId`)
VALUES
    ('Hospital Maisonneuve Rosemont', 1, '306-664-1466', 'www.HospitalMaisonneuveRosemont.com', 1, 100,11),
    ('Grand University Medical Clinic', 2, '204-294-9797', 'www.GrandUniversityMedicalClinic.com', 2, 90,12),
    ('Amity Medical Center', 3, '780-786-9953', 'www.AmityMedicalCenter.com', 3, 80,13),
    ('Bellevue Medical Center', 4, '613-453-9547', 'www.BellevueMedicalCenter.com', 4, 70,14),
    ('Hillsdale Community Hospital', 5, '780-436-3749', 'www.HillsdaleCommunityHospital.com', 5, 60,15),
    ('Olympus Medical Clinic', 6, '416-962-6849', 'www.OlympusMedicalClinic.com', 1, 50,16),
    ('Archangel General Hospital', 7, '647-403-7225', 'www.ArchangelGeneralHospital.com', 2, 40,17),
    ('Oak Crest Clinic', 8, '306-751-8617', 'www.OakCrestClinic.com', 3, 30,18),
    ('Grand Meadow Medical Clinic', 9, '819-352-5569', 'www.GrandMeadowMedicalClinic.com', 4, 20,19),
    ('Principal Medical Clinic', 10, '416-532-9330', 'www.PrincipalMedicalClinic.com', 5, 10,20);


-- -----------------------------------------------------
-- Table `mydb`.`employment`
-- -----------------------------------------------------
INSERT INTO `mydb`.`employment` (`employeeId`, `startDate`, `endDate`, `facilityId`)
VALUES
    (1, '2023-04-01', '2023-04-05', 10),
    (1, '2023-04-01', '2023-04-05', 9),
    (4, '2023-04-01', '2023-04-05', 7),
    (5, '2023-04-01', '2023-04-05', 6),
    (6, '2023-04-01', '2023-04-05', 5),
    (7, '2023-04-01', '2023-04-05', 4),
    (8, '2023-04-01', '2023-04-05', 3),
    (9, '2023-04-01', '2023-04-05', 2),
    (10, '2023-04-01', '2023-04-05', 1),
    (10, '2023-04-01', '2023-04-05', 8),
    (2, '2023-04-01', NULL, 1),
    (3, '2023-04-01', NULL, 1),
    (4, '2023-04-01', NULL, 1),
    (5, '2023-04-01', '2023-04-05', 1),
    (11, '2023-04-01', '2023-04-05', 1),
    (11, '2023-04-01', '2023-04-05', 2);


-- -----------------------------------------------------
-- Table `mydb`.`vaccination_type`
-- -----------------------------------------------------
INSERT INTO `mydb`.`vaccination_type` (`type`)
VALUES
    ('Pfizer'),
    ('Moderna'),
    ('AstraZeneca'),
    ('Johnson & Johnson');


-- -----------------------------------------------------
-- Table `mydb`.`vaccination`
-- -----------------------------------------------------
INSERT INTO `mydb`.`vaccination` (`employeeId`, `dose`, `typeId`, `date`, `facilityId`)
VALUES
    (1, 1, 1, date('2020-05-01'), 1),
    (2, 1, 3, date('2020-07-11'), 2),
    (3, 1, 2, date('2020-07-12'), 3),
    (4, 2, 3, date('2020-09-03'), 4),
    (5, 1, 4, date('2020-11-07'), 5),
    (6, 2, 3, date('2021-08-08'), 6),
    (7, 2, 1, date('2021-11-01'), 7),
    (8, 2, 2, date('2022-06-21'), 9),
    (9, 1, 1, date('2022-07-01'), 8),
    (10, 1, 1, date('2022-09-10'), 10);


-- -----------------------------------------------------
-- Table `mydb`.`infection_type`
-- -----------------------------------------------------
INSERT INTO `mydb`.`infection_type` (`type`)
VALUES
    ('COVID-19'),
    ('SARS-Cov-2'),
    ('Other');

-- -----------------------------------------------------
-- Table `mydb`.`infection`
-- -----------------------------------------------------
INSERT INTO `mydb`.`infection` (`employeeId`, `date`, `infectionTypeId`)
VALUES
    (1, '2023-04-01', 1),
    (2, '2023-03-28', 2),
    (3, '2023-03-30', 3),
    (4, '2023-04-02', 1),
    (5, '2023-03-29', 2),
    (6, '2023-04-03', 3),
    (7, '2023-04-04', 1),
    (8, '2023-03-31', 2),
    (9, '2023-04-05', 3),
    (10, '2023-04-06', 1);


-- -----------------------------------------------------
-- Table `mydb`.`schedule`
-- -----------------------------------------------------
INSERT INTO `mydb`.`schedule` (`date`, `startTime`, `endTime`, `isCancelled`,`employmentId`)
VALUES
    ('2023-04-01', '09:00:00', '17:00:00', 1, 1),
    ('2023-04-02', '09:00:00', '17:00:00', 1, 2),
    ('2023-04-03', '09:00:00', '17:00:00', 1, 3),
    ('2023-04-04', '09:00:00', '17:00:00', 1, 4),
    ('2023-04-01', '08:00:00', '16:00:00', 1,5),
    ('2023-04-02', '08:00:00', '16:00:00', 1,6),
    ('2023-04-03', '08:00:00', '16:00:00', 1,7),
    ('2023-04-04', '08:00:00', '16:00:00', 1,8),
    ('2023-04-01', '10:00:00', '18:00:00', 1,9),
    ('2023-04-02', '10:00:00', '18:00:00', 0,11),
    ('2023-04-03', '10:00:00', '18:00:00', 0,12),
    ('2023-04-04', '10:00:00', '18:00:00', 0,1),
    ('2023-04-01', '08:30:00', '16:30:00', 0,2),
    ('2023-04-02', '08:30:00', '16:30:00', 1,3),
    ('2023-04-03', '08:30:00', '16:30:00', 0,4),
    ('2023-04-04', '08:30:00', '16:30:00', 0,5),
    ('2023-04-01', '09:30:00', '17:30:00', 0,6),
    ('2023-04-02', '09:30:00', '17:30:00', 1,7),
    ('2023-04-03', '09:30:00', '17:30:00', 0,8),
    ('2023-04-04', '09:30:00', '17:30:00', 0,9);

