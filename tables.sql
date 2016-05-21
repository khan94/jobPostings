	

drop table APPLICANT_HAS_SKILLS;
drop table JOB_REQUIRES_SKILLS;
drop table APPLY;
drop table JOB_OFFERS;
drop table JOB_POSTINGS;
drop table APPLICANTS;
drop table EMPLOYER_RUNS;
drop table SKILLS;
drop table COMPANIES;
drop table COMPANY_LOCATIONS;
drop table INTERVIEWS;






create table APPLICANTS(
app_login varchar(100) PRIMARY KEY,
sin integer,
name varchar(100),
phone_number integer,
password varchar(100),
email varchar(100),
address varchar(100));

create table SKILLS(
skill varchar(100) PRIMARY KEY);

create table COMPANIES (
c_id integer PRIMARY KEY,
company_name varchar(100));

create table COMPANY_LOCATIONS(
b_id integer,
c_id integer,
PRIMARY KEY(b_id, c_id),
address varchar(100));

create table INTERVIEWS (
interview_id integer PRIMARY KEY,
accepted char(1),
interview_date date,
interview_time varchar(100),
address varchar(100));

create table EMPLOYER_RUNS(
emp_login varchar(100) PRIMARY KEY,
sin integer,
name varchar(100),
phone_number integer,
password varchar(100),
email varchar(100),
address varchar(100),
b_id integer,
c_id integer,
FOREIGN KEY (b_id, c_id) REFERENCES COMPANY_LOCATIONS(b_id, c_id)
ON DELETE CASCADE);

create table JOB_POSTINGS(
job_id integer PRIMARY KEY,
login varchar(100) not null,
description varchar(100),
posting_date date,
job_title varchar(100),
salary integer,
FOREIGN KEY (login) REFERENCES EMPLOYER_RUNS(emp_login)
ON DELETE CASCADE);

create table JOB_OFFERS(
employer_login varchar(100),
applicant_login varchar(100),
job_id integer,
PRIMARY KEY (employer_login,applicant_login,job_id),
FOREIGN KEY (employer_login) REFERENCES EMPLOYER_RUNS(emp_login)
ON DELETE CASCADE,
FOREIGN KEY (applicant_login) REFERENCES APPLICANTS(app_login)
ON DELETE CASCADE,
FOREIGN KEY (job_id) REFERENCES JOB_POSTINGS(job_id)
ON DELETE CASCADE);

create table JOB_REQUIRES_SKILLS (
jobID integer,
skill varchar(100),
proficiency int,
PRIMARY KEY (jobID, skill),
FOREIGN KEY (skill) REFERENCES SKILLS(skill)
ON DELETE CASCADE,
FOREIGN KEY (jobID) REFERENCES JOB_POSTINGS(job_id)
ON DELETE CASCADE);

create table APPLICANT_HAS_SKILLS (
login varchar(100),
skill varchar(100),
proficiency int,
PRIMARY KEY (login, skill),
FOREIGN KEY (login) REFERENCES APPLICANTS(app_login)
ON DELETE CASCADE,
FOREIGN KEY (skill) REFERENCES SKILLS(skill)
ON DELETE CASCADE);

create table APPLY (
login varchar(100),
job_id integer,
iv_id integer,
PRIMARY KEY (login, job_id),
FOREIGN KEY (login) REFERENCES APPLICANTS(app_login)
ON DELETE CASCADE,
FOREIGN KEY (job_id) REFERENCES JOB_POSTINGS(job_id)
ON DELETE CASCADE,
FOREIGN KEY (iv_id) REFERENCES INTERVIEWS(interview_id)
ON DELETE CASCADE);