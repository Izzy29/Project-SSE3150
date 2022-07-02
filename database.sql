CREATE TABLE admin(
admin_id INT(100) PRIMARY KEY AUTO_INCREMENT,
admin_name VARCHAR(100),
password VARCHAR(100)
);

CREATE TABLE define (
defineId INT(11) PRIMARY KEY AUTO_INCREMENT,
level VARCHAR(20),
definition VARCHAR(530),
score_min INT(11),
score_max INT(11)
);

INSERT INTO define VALUES (1, 'Sustaining', 'Project has low risk and complexity. The project outcome affects only a specific service or at most a specific program, and risk mitigations for general project risks are in place. The project does not consume a significant percentage of departmental or agency resources.', 0, 44);
INSERT INTO define VALUES (2, 'Tactical', 'A project rated at this level affects multiple services within a program and may involve more significant procurement activities. It may involve some information management or information technology (IM/IT) or engineering activities. The project risk profile may indicate that some risks could have serious impacts, requiring carefully planned responses. The scope of a tactical project is operational in nature and delivers new capabilities within limits.', 45, 63);
INSERT INTO define VALUES (3, 'Evolutionary', 'As indicated by the name, projects within this level of complexity and risk introduce change, new capabilities and may have a fairly extensive scope. Disciplined skills are required to successfully manage evolutionary projects. Scope frequently spans programs and may affect one or two other departments or agencies. There may be substantial change to business process, internal staff, external clients, and technology infrastructure. IM/IT components may represent a significant proportion of total project activity.', 64, 82);
INSERT INTO define VALUES (4, 'Transformational', 'At this level, projects require extensive capabilities and may have a dramatic impact on the organization and potentially other organizations. Horizontal (i.e. multi-departmental, multi-agency, or multi-jurisdictional) projects are transformational in nature. Risks associated with these projects often have serious consequences, such as restructuring the organization, change in senior management, and/or loss of public reputation.', 83, 100);

CREATE TABLE manager(
manager_id INT(11) AUTO_INCREMENT PRIMARY KEY,
firstName VARCHAR(100),
lastName VARCHAR(100),
position VARCHAR(100),
username VARCHAR(100),
password VARCHAR(100),
specialPin INT(100),
attempt INT(100)
);

CREATE TABLE mode (
    modeId int PRIMARY KEY,
    modeName varchar(12));

INSERT INTO mode VALUES (1, 'Insource');
INSERT INTO mode VALUES (2, 'Outsource');
INSERT INTO mode VALUES (3, 'Co-source');
INSERT INTO mode VALUES (4, 'Unspecified');

CREATE TABLE project (
   	projectId int AUTO_INCREMENT PRIMARY KEY,
   	projectName varchar(30),
  	owner varchar(30),
   	funds int(11),
   	duration int(11),
    	modeId int(11),
	manager_id int(100),
    	CONSTRAINT proj_mode_uk FOREIGN KEY(modeId) REFERENCES mode(modeId),
	FOREIGN KEY (manager_id) REFERENCES manager(manager_id));

CREATE TABLE section (
    sectionId int PRIMARY KEY,
    sectionName varchar(50));

CREATE TABLE questions (
    questionNum int PRIMARY KEY,
    question varchar(650),
    sectionId int,
    CONSTRAINT ques_sect_uk FOREIGN KEY(sectionId) REFERENCES section(sectionId));

CREATE TABLE rating (
    ratingId int AUTO_INCREMENT PRIMARY KEY,
    ratingValue int,
    ratingText varchar(250),
    questionNum int,
    CONSTRAINT rate_quest_uk FOREIGN KEY(questionNum) REFERENCES questions(questionNum));

CREATE TABLE result (
result_id INT(100) AUTO_INCREMENT PRIMARY KEY,
section1 INT(100),
section2 INT(100),
section3 INT(100),
section4 INT(100),
section5 INT(100),
section6 INT(100),
section7 INT(100),
manager_id INT(100),
projectId INT(100),
FOREIGN KEY (manager_id) REFERENCES manager(manager_id),
FOREIGN KEY (projectId) REFERENCES project(projectId)
);
);