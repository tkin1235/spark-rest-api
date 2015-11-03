<?php

	/**
	 * Created by PhpStorm.
	 * User: kisgal21
	 * Date: 11/1/15
	 * Time: 2:55 PM
	 */
	class stories
	{
		private $userModel;
		/** @var shiftModel $shiftModel */
		private $shiftModel;
		/** @var  tokensModel $tokenModel */
		private $tokenModel;

		function __construct(shiftModel $shiftModel, userModel $userModel, tokensModel $tokenModel, $token)
		{
			if (!$shiftModel) {
				throw new \Exception('shiftModel is required');
			}
			if (!$userModel) {
				throw new \Exception('userModel is required');
			}
			$this->userModel = $userModel;
			$this->shiftModel = $shiftModel;
			$this->tokenModel = $tokenModel;
			$this->token = $token;
		}

		/**
		 * checks employee access tokens
		 * @return bool
		 * @throws Exception
		 */
		private function setEmployeeAccess()
		{

			if ($this->tokenModel->getTokenAccess($this->token) === 'Employee') {
				return true;
			} else {
				throw new \Exception('user does not have access!');
			}
		}

		/**
		 * Story 1:
		 * As an employee, I want to know when I am working, by being able to see all of the shifts assigned to me.
		 * aka Get by user ID
		 * @param $userID
		 * @return array
		 * @throws Exception
		 * @throws userModelException
		 */
		public function shiftsForEmployee($userID)
		{    //story 1

			$this->setEmployeeAccess();

			$employee = $this->userModel->findUserByArray([
				$this->userModel->userIdField => $userID,
			]);

			if (empty ($employee)) {
				throw new \Exception('Employee not found');
			} else {
				return $this->shiftModel->getShiftsByEmployee(array_values($employee)[0]);
			}
		}


		/**
		 * Story 2:
		 * As an employee, I want to know who I am working with, by being able to see the employees that are working during the same time period as me.
		 * @param $startTime
		 * @param $endTime
		 * @return array
		 * @throws Exception
		 */
		public function getEmployeesWorkingBetween($startTime, $endTime)
		{ //story 2
			$this->setEmployeeAccess();
			$employeeIds = $this->shiftModel->getEmployeesWorkingBetween($startTime, $endTime);
			$employees = $this->userModel->getUsersByIds($employeeIds);

			return $employees;
		}

		/**
		 * As an employee, I want to know how much I worked, by being able to get a summary of hours worked for each week.
		 * @param $userID
		 * @param $startTime
		 * @param $endTime
		 * @return mixed
		 * @throws Exception
		 */
		public function getHoursForEmployeeBetween($userID, $startTime, $endTime)
		{    //story 3
			$this->setEmployeeAccess();

			$employeeObject = $this->userModel->getUserById($userID);
			$employeeID = $employeeObject['_id'];
			$shifts = $this->shiftModel->getShiftsForEmployeeBetween($employeeID, $startTime, $endTime);

			$formattedShifts = [];
			foreach ($shifts as $shift) {
				$formattedShifts [] = ['shift' => ['start' => $shift['start_time']->sec, 'end' => $shift['end_time']->sec]];
			}

			return $formattedShifts;
		}

		/**
		 * Story 4:
		 * As an employee, I want to be able to contact my managers, by seeing manager contact information for my shifts.
		 * @param $userID
		 * @return array
		 * @throws Exception
		 */
		public function getManagersByEmployeeID($userID)
		{
			$this->setEmployeeAccess();
			$shifts = $this->getShiftsByEmployeeID($userID);
			$managerIDs = [];

			foreach ($shifts as $shift) {
				$managerIDs [] = $shift['manager_id'];
			}

			$managers = $this->userModel->getUsersByIds($managerIDs);

			return $managers;

		}

		/**
		 * Story 5:
		 * As a manager, I want to schedule my employees, by creating shifts for any employee.
		 * @param $employeeID
		 * @param $startTime
		 * @param $endTime
		 * @param float $break
		 * @return array
		 * @throws Exception
		 */
		public function createShiftForEmployee($employeeID, $startTime, $endTime, $break = 0.25)
		{
			$this->setManagerAccess();
			$managerID = $this->tokenModel->getUserFromToken($this->token);

			return $this->shiftModel->createShift($employeeID, $managerID, $startTime, $endTime, $break);

		}

		/**
		 * Check access token for manager only access
		 * @return bool
		 * @throws Exception
		 */
		private function setManagerAccess()
		{
			if ($this->tokenModel->getTokenAccess($this->token) === 'Manager') {
				return true;
			} else {
				throw new \Exception('user does not have access!');
			}
		}

		/**
		 * Story 6:
		 * As a manager, I want to see the schedule, by listing shifts within a specific time period.
		 * @param $startTime
		 * @param $endTime
		 * @return array
		 * @throws Exception
		 */
		public function getShiftsBetween($startTime, $endTime)
		{
			$this->setManagerAccess();

			return $this->shiftModel->getShiftsBetween($startTime, $endTime);
		}

		/**
		 * Story 7:
		 * As a manager, I want to be able to change a shift, by updating the time details.
		 * @param $shiftID
		 * @param $startTime
		 * @param $endTime
		 * @return bool
		 * @throws Exception
		 */
		public function updateShift($shiftID, $startTime, $endTime)
		{
			$this->setManagerAccess();

			return $this->shiftModel->updateShift($shiftID, $startTime, $endTime);
		}

		/**
		 * Story 8:
		 * As a manager, I want to be able to assign a shift, by changing the employee that will work a shift.
		 * @param $employeeID
		 * @param $shiftID
		 * @return bool
		 * @throws Exception
		 * @throws userModelException
		 */
		public function assignEmployeeToShift($employeeID, $shiftID)
		{
			$this->setManagerAccess();
			$employee = $this->userModel->getUserById($employeeID);

			return $this->shiftModel->assignEmployeeToShift($employee, $shiftID);
		}

		/**
		 * Story 9:
		 * As a manager, I want to contact an employee, by seeing employee details.
		 * @param $employeeID
		 * @return array|null
		 * @throws Exception
		 * @throws userModelException
		 */
		public function getEmployeeDetails($employeeID)
		{
			$this->setManagerAccess();

			return $this->userModel->getUserById($employeeID);
		}


		/**
		 * Gets shifts from user ID (string)
		 * @param String $userID
		 * @return array
		 * @throws userModelException
		 */
		private function getShiftsByEmployeeID($userID)
		{
			$userObject = $this->userModel->getUserById($userID);
			$shifts = $this->shiftModel->getShiftsByEmployee($userObject);

			return $shifts;
		}

	}