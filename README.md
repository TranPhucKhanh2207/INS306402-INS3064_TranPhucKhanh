## Part 1: Normalization
#Task 1 — Identify violations
1. Which columns lead to redundancy?
StudentName: This repeats every time a single student enrolls in multiple courses (e.g., "Nguyen An" is listed twice for StudentID 1).
CourseName: This repeats every time a different student takes the same course (e.g., "Database Systems" is listed multiple times).
ProfessorName and ProfessorEmail: These repeat for every single student enrolled in a course taught by that professor.
2. Where could update anomalies happen?
Email change (Update Anomaly): If Dr. Le updates his email address, you are forced to find and update every single row of every student taking his class. If you update the email for student 1 but forget to update it for student 2, your database will have contradictory information (data inconsistency).
Course rename (Update Anomaly): Similarly, if the university renames "Database Systems" to "Intro to Databases," you have to change it in multiple rows instead of just one central location.
3. Is there any transitive dependency?
Yes. A transitive dependency happens when a non-key column depends on another non-key column.
The specific violation: ProfessorName functionally depends on ProfessorEmail. If you know the email, you know the professor's name. Because neither of these columns acts as the Primary Key for this specific table (the true Primary Key here would have to be the combination of StudentID and CourseID to determine the Grade), this creates a transitive dependency that violates the Third Normal Form (3NF).\

#Task 2 — Decompose to 3NF
| Table Name | Primary Key | Foreign Key | Non-key columns |
| :--- | :--- | :--- | :--- | :--- |
| Students | StudentID | None | StudentName |
| Professors | ProfessorEmail | None | ProfessorName |
| Courses | CourseID | ProfessorEmail | CourseName | 
| Enrollments | StudentID, CourseID | StudentID, CourseID | Grade |
Students: This table isolates student profile information. It ensures that a StudentName is only recorded once per student, preventing redundancy when they enroll in multiple classes.
Professors: This table stores professor details. Since the raw data lacks a Professor ID, ProfessorEmail is used as a natural Primary Key because it is unique. This eliminates update anomalies; if a professor changes their name or email, it only needs to be updated in one single row here.
Courses: This table stores course details and assigns the teaching professor. ProfessorEmail acts as a Foreign Key linking to the Professors table, establishing a one-to-many relationship (one professor can teach multiple courses, but each course instance is taught by one professor).
Enrollments: This is a junction table required to resolve the many-to-many relationship between Students and Courses. The Primary Key must be a composite key (StudentID, CourseID). The Grade column is placed here because a grade is functionally dependent on both the specific student and the specific course.
## Part 2: Relationship Drills
1.AUTHOR — BOOK
Relationship Type: Many-to-Many (M:N). An author can write multiple books, and a single book can be written by multiple authors (co-authors).
Foreign Key Location: You cannot put the FK directly into either table. You must create a junction table (e.g., Author_Book). The FKs (AuthorID and BookID) will both be placed inside this new junction table.
2. CITIZEN — PASSPORT
Relationship Type: One-to-One (1:1). A citizen has one active passport, and a specific passport belongs to exactly one citizen.
Foreign Key Location: In a strict 1:1, the FK can technically go in either table. However, best practice places the FK in the dependent table. Since a passport cannot exist without a citizen, place the FK (CitizenID) in the Passport table.
3. CUSTOMER — ORDER
Relationship Type: One-to-Many (1:M). A customer can place many orders, but a specific order belongs to exactly one customer.
Foreign Key Location: The rule is that the FK always goes on the "Many" side. Therefore, place the FK (CustomerID) in the Order table.
4. STUDENT — CLASS
Relationship Type: Many-to-Many (M:N). A student enrolls in many classes, and a class contains many students. (This is exactly like the Enrollments scenario you solved in Part 1).
Foreign Key Location: Just like Part 1, you must create a junction table (e.g., Enrollment). The FKs (StudentID and ClassID) go into this new junction table.
5. TEAM — PLAYER
Relationship Type: One-to-Many (1:M). A team has many players, but a player plays for only one team at a time.
Foreign Key Location: The FK goes on the "Many" side. Therefore, place the FK (TeamID) in the Player table.
