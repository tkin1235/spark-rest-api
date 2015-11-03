<?php


	namespace Spark\Project\Domain;

	use Spark\Adr\DomainInterface;
	use Spark\Payload;


	class Hello implements DomainInterface
	{

		private $token;
		/** @var Array $headers */
		private $headers;
		/** @var \stories $stories */
		private $stories;
		/** @var \userModel $userModel */
		private $userModel;
		/** @var \shiftModel $shiftModel */
		private $shiftModel;
		/** @var \tokensModel $tokensModel */
		private $tokensModel;

		function __construct()
		{
			require_once(__DIR__ . '/../Models/MongoModel.php');
			require_once(__DIR__ . '/../Models/tokenModel.php');
			require_once(__DIR__ . '/../Models/shiftModel.php');
			require_once(__DIR__ . '/../Models/userModel.php');
			require_once(__DIR__ . '/../Stories/stories.php');

			$dbClient = new \MongoClient();
			$db = $dbClient->selectDB('spark');

			$this->setHeaders();
			$this->setTokenFromHeaders();

			$usersCollection = $db->selectCollection('users');
			$shiftCollection = $db->selectCollection('shift');
			$tokensCollection = $db->selectCollection('tokens');

			$this->userModel = new \userModel($usersCollection);
			$this->shiftModel = new \shiftModel($shiftCollection);
			$this->tokensModel = new \tokensModel($tokensCollection, $this->token);
		}


		private function setTokenFromHeaders()
		{
			$this->token = $this->headers['token'];
		}

		private function setHeaders()
		{
			$this->headers = getallheaders();
		}

		public function __invoke(array $input)
		{

			$this->stories = new \stories($this->shiftModel, $this->userModel, $this->tokensModel, $this->token);

			if (!empty($input['name'])) {
				$name = $input['name'];
			}

			if (!empty($input['employeeID'])) {
				$employeeID = $input['employeeID'];
				$return = $this->stories->shiftsForEmployee($employeeID);
			}

			if (!empty($input['startTime']) && !empty($input['endTime'])) {

				$start = $input['startTime'];
				$end = $input['endTime'];

				$startTime = \DateTime::createFromFormat('m-d-Y', $start);
				$endTime = \DateTime::createFromFormat('m-d-Y', $end);

				$return = $this->stories->getEmployeesWorkingBetween($startTime, $endTime);
			}

			if (!empty($input['employeeID']) && !empty($input['startTime']) && !empty($input['endTime'])) {
				$start = $input['startTime'];
				$end = $input['endTime'];
				$employeeID = $input['employeeID'];

				$startTime = \DateTime::createFromFormat('m-d-Y', $start);
				$endTime = \DateTime::createFromFormat('m-d-Y', $end);

				$return = $this->stories->getHoursForEmployeeBetween($employeeID, $startTime, $endTime);
			}

			if (!empty($input['employeeI2D'])) {
				$employeeID = $input['employeeID2'];
				$return = $this->stories->shiftsForEmployee($employeeID);

			}


			return (new Payload)
				->withStatus(Payload::OK)
				->withOutput([
					'hello' => $return,
				]);
		}
	}


