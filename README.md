# spark-rest-api
rest API built in spark ADR framework

This is an attempt at a restAPI built with spark ADR (https://github.com/sparkphp/spark) to fulfull the following requirements,
as fully specified at https://raw.githubusercontent.com/wheniwork/standards/master/project.md

## User stories

**Please note that this not intended to be a CRUD application.** Only the functionality described by the user stories should be exposed via the API.

- [ ] As an employee, I want to know when I am working, by being able to see all of the shifts assigned to me.
- [ ] As an employee, I want to know who I am working with, by being able to see the employees that are working during the same time period as me.
- [ ] As an employee, I want to know how much I worked, by being able to get a summary of hours worked for each week.
- [ ] As an employee, I want to be able to contact my managers, by seeing manager contact information for my shifts.

- [ ] As a manager, I want to schedule my employees, by creating shifts for any employee.
- [ ] As a manager, I want to see the schedule, by listing shifts within a specific time period.
- [ ] As a manager, I want to be able to change a shift, by updating the time details.
- [ ] As a manager, I want to be able to assign a shift, by changing the employee that will work a shift.
- [ ] As a manager, I want to contact an employee, by seeing employee details.

