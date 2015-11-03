<?php

	/**
	 * Class dbModel
	 */
	abstract class dbModel
	{
		/** @var  \MongoCollection $collection */
		protected $collection;

		/**
		 * pass in collection during instantiation
		 * @param $collection
		 * @throws modelException
		 */
		function __construct($collection)
		{
			if (!$collection instanceof \MongoCollection) {
				throw new modelException('mongo collection is required!');
			}

			$this->setCollection($collection);
		}

		/**
		 * set collection to class
		 * @param $collection
		 */
		protected function setCollection($collection)
		{
			$this->collection = $collection;
		}

		/**
		 * @unused?
		 * generalized mongo query
		 * @param array $query
		 * @param MongoCollection $collection
		 * @return array
		 * @throws modelException
		 */
		protected function findByArray($query = [], \MongoCollection $collection)
		{
			if (count($query) < 1) {
				throw new modelException('array is required');
			}

			return iterator_to_array($collection->find($query));
		}
	}

	class modelException extends Exception
	{

	}