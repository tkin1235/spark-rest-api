<?php

	/**
	 * Created by PhpStorm.
	 * User: kisgal21
	 * Date: 11/1/15
	 * Time: 2:37 PM
	 */
	class shiftModel extends dbModel
	{
		function __construct(\MongoCollection $shiftCollection)
		{
			if (!$shiftCollection instanceof \MongoCollection) {
				throw new userModelException('mongo collection is required!');
			}

			parent::__construct($shiftCollection);
			$this->setCollection($shiftCollection);
		}

		/** @var array $shift schema */
		private $shift = [
			'manager_id'  => '',    //reference
			'employee_id' => '',  //ref
			'break'       => 0.0,           //float
			'start_time'  => '',    //date
			'end_time'    => '',
			'created_at',
			'updated_at'

		];

		/**
		 * @param $userID
		 * @param \DateTime $startTime
		 * @param \DateTime $endTime
		 * @return array
		 */
		public function getShiftsForEmployeeBetween($userID, $startTime, $endTime)
		{

			$query = [
				'employee_id' => (string)$userID,
				'start_time'  => [
					'$gte' => new MongoDate($startTime->getTimestamp())
				],
				'end_time'    => [
					'$lte' => new MongoDate($endTime->getTimestamp())
				]
			];

			$shifts = $this->collection->find($query);

			return iterator_to_array($shifts);
		}

		/**
		 * @param \DateTime $startTime
		 * @param \DateTime $endTime
		 * @return array
		 */
		public function getShiftsBetween($startTime, $endTime)
		{

			$query = [
				'start_time' => [
					'$gte' => new MongoDate($startTime->getTimestamp())
				],
				'end_time'   => [
					'$lte' => new MongoDate($endTime->getTimestamp())
				]
			];

			$shifts = $this->collection->find($query);

			return iterator_to_array($shifts);
		}

		/**
		 * @param $employeeID
		 * @param $managerID
		 * @param \DateTime $startTime
		 * @param \DateTime $endTime
		 * @param float $break
		 * @return array
		 */
		public function createShift($employeeID, $managerID, $startTime, $endTime, $break = 0.25)
		{

			$date = new MongoDate();
			$shift = [
				'break'       => $break,
				'manager_id'  => $managerID,
				'created_at'  => $date,
				'employee_id' => $employeeID,
				'updated_at'  => $date,
				'id'          => '',    //shiftID
				'_id'         => null,  //Mongo internal ID
				'start_time'  => new MongoDate($startTime->getTimeStamp()),
				'end_time'    => new MongoDate($endTime->getTimeStamp())
			];

			$this->collection->insert($shift);

			return $shift;
		}

		/**
		 * @param \DateTime $startTime
		 * @param \DateTime $endTime
		 * @return Array
		 */
		public function getEmployeesWorkingBetween($startTime, $endTime)
		{
			$shifts = $this->collection->find([
				'start_time' => [
					'$gte' => new MongoDate($startTime->getTimestamp())
				],
				'end_time'   => [
					'$lte' => new MongoDate($endTime->getTimestamp())
				]
			]);

			$employeeIDs = [];
			foreach ($shifts as $shift) {
				$employeeIDs [] = $shift['employee_id'];
			}

			return $employeeIDs;
		}

		/**
		 * @param $employee
		 * @return array
		 */
		public function getShiftsByEmployee($employee)
		{

			$shifts = $this->collection->find([
				'employee_id' => (string)$employee['_id']
			]);

			return iterator_to_array($shifts);
		}

		/**
		 * @param $shiftID
		 * @param \DateTime $startTime
		 * @param \DateTime $endTime
		 * @return bool
		 */
		public function updateShift($shiftID, $startTime, $endTime)
		{
			$query = [
				'id' => $shiftID
			];
			$newRecord = [
				'$set' => [
					'start_time' => new MongoDate($startTime->getTimestamp()),
					'end_time'   => new MongoDate($endTime->getTimestamp())
				]
			];

			return $this->collection->update($query, $newRecord);
		}

		public function assignEmployeeToShift($employee, $shiftID)
		{


			$query = [
				'id' => $shiftID
			];

			$newRecord = [
				'$set' => [
					'employee_id' => (string)$employee['_id']
				]
			];

			return $this->collection->update($query, $newRecord);

		}

	}
