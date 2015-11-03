<?php

	/**
	 * Created by PhpStorm.
	 * User: kisgal21
	 * Date: 11/1/15
	 * Time: 2:37 PM
	 */
	class tokensModel extends dbModel
	{

		function __construct(\MongoCollection $tokenCollection)
		{
			parent::__construct($tokenCollection);
			$this->setCollection($tokenCollection);

		}

		/**
		 * Gets token from DB
		 * @TODO these should be hashed or encrypted
		 * @param $token
		 * @return array|null
		 * @throws Exception
		 */
		private function getToken($token)
		{
			$tokenResult = $this->collection->findOne([
				'token' => $token
			]);

			if (empty($tokenResult)) {
				throw new \Exception('token not found!');
			} else {
				return $tokenResult;
			}
		}

		/**
		 * Get field on token
		 * @todo don't hardcode these indices, pull them from a the schema
		 * @param $token
		 * @return mixed
		 * @throws Exception
		 */
		public function getTokenAccess($token)
		{
			if (!$access = $this->getToken($token)['access']) {
				throw new \Exception('token access not found!');
			}

			return $access;
		}

		/**
		 * Gets user from token
		 * @todo don't hardcode these indices, pull them from a the schema
		 * @param $token
		 * @return mixed
		 * @throws Exception
		 */
		public function getUserFromToken($token)
		{
			if (!$userID = $this->getToken($token)['user_id']) {
				throw new \Exception('token user not found!');
			}

			return $userID;
		}

	}
