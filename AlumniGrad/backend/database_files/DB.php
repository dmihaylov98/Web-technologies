<?php
    class Database {
        private $connection;
        private $insertStudentStatement;
        private $insertUserStatement;
        private $insertAdministrationStatement;
        private $selectUserStatement;
        private $deleteUserStatement;
        private $importDataStatement;
        private $rowCountStatement;
        private $findMarkStatement;
        private $updateMarkStatement; 
        private $participationStatement;
        private $returnStudentStatement;
        private $insertParticipantStatement;
        private $insertExtraInfoStatement;
        private $returnExtraInfoStatement;
        private $returnAwardedStatement;
        private $totalAttendanceStatement;
        private $graduationOrderStatement;
        private $togaNotReturnedStatement;
        private $hatListStatement;
        private $returnAdmStatement;
        private $awardStatement;
        private $checkParticipationStatement;
        private $returnedTogaStatement;
        private $togaTakenStatement; 
        private $signatureStatement; 
        private $excellentStatement;
        private $speechSelectedStatement;

        public function __construct() {
            $config = parse_ini_file("config-db.ini", true);

            $host = $config['db']['host'];
            $dbname = $config['db']['name'];
            $user = $config['db']['user'];
            $password = $config['db']['password'];

            try {
                $this->connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->prepareStatements();

                $this->rowCountStatement->execute();
                $numberOfRows = $this->rowCountStatement->fetchColumn();
                if ($numberOfRows == 0) { 
                    $this->getStudents();
                }
                
            } catch(PDOException $e) {
                return "Connection to the database failed: " . $e->getMessage();
            }
        }

        private function getStudents() {
            $file = fopen(__DIR__ . "/graduating students.csv", "r");

            while (!feof($file)) {
                $content = fgets($file);
                $carray = explode(",", $content);
                list($gradFn, $gradMark) = $carray;
                $this->importDataStatement->execute(["gradFn" => $gradFn, "gradMark" => $gradMark]);
            }
        }

        private function prepareStatements() {
            $sqlUsers = "INSERT INTO users(username, password, role) VALUES (:username, :password, :role)";
            $this->insertUserStatement = $this->connection->prepare($sqlUsers);

            $sqlStudent = "INSERT INTO student(fn, username, name, surname, email, major, degree, mobile) 
                VALUES (:fn, :username, :name, :surname, :email, :major, :degree, :mobile)";
            $this->insertStudentStatement = $this->connection->prepare($sqlStudent);

            $sqlAdministration = "INSERT INTO administration(username, name, surname, email, position)
                VALUES (:username, :name, :surname, :email, :position)";
            $this->insertAdministrationStatement = $this->connection->prepare($sqlAdministration);

            $selectUser = "SELECT * FROM users WHERE username = :username";
            $this->selectUserStatement = $this->connection->prepare($selectUser);

            $deleteUser = "DELETE FROM users WHERE username = :username";
            $this->deleteUserStatement = $this->connection->prepare($deleteUser);

            $importData = "INSERT INTO `graduating students`(gradFn, gradMark) VALUES (:gradFn, :gradMark)";
            $this->importDataStatement = $this->connection->prepare($importData);

            $countRows = "SELECT COUNT(*) FROM `graduating students`";
            $this->rowCountStatement = $this->connection->prepare($countRows);

            // find the mark
            $findMark = "SELECT gradMark FROM `graduating students` WHERE gradFn = :fn";
            $this->findMarkStatement = $this->connection->prepare($findMark);

            // update the mark
            $updateMark = "UPDATE student SET mark = :mark WHERE fn = :fn";
            $this->updateMarkStatement = $this->connection->prepare($updateMark);

            $participate = "UPDATE student SET participate = :val WHERE username = :username";
            $this->participationStatement = $this->connection->prepare($participate);

            $partStat = "SELECT COUNT(st1.username) AS `all`, COUNT(st2.username) AS `attend` FROM student st1 LEFT JOIN student st2 ON st1.fn = st2.fn AND st2.participate = 1";
            $this->participationStatStatement = $this->connection->prepare($partStat);

            $retStudent = "SELECT * FROM student WHERE username = :username";
            $this->returnStudentStatement = $this->connection->prepare($retStudent);

            $insParticipant = "INSERT INTO participants(fn) VALUES(:fn)";
            $this->insertParticipantStatement = $this->connection->prepare($insParticipant);

            $extraInfo = "UPDATE participants SET toga = :toga, hat = :hat, speech = :speech, places = :places WHERE fn = :fn";
            $this->insertExtraInfoStatement = $this->connection->prepare($extraInfo);

            $retExtraInfo = "SELECT toga, speech, places, hat FROM student JOIN participants ON student.fn = participants.fn WHERE student.username = :username";
            $this->returnExtraInfoStatement = $this->connection->prepare($retExtraInfo);

            $awardedStud = "SELECT name, surname, student.fn, prize FROM participants JOIN student ON student.fn = participants.fn WHERE prize IS NOT NULL ORDER BY name ASC, surname ASC";
            $this->returnAwardedStatement = $this->connection->prepare($awardedStud);

            $attendance = "SELECT SUM(places) as `total places` FROM participants";
            $this->totalAttendanceStatement = $this->connection->prepare($attendance);

            $gradOrd = "SELECT name, surname, student.fn, major, degree FROM participants JOIN student on participants.fn = student.fn ORDER BY degree ASC, major ASC, mark DESC";
            $this->graduationOrderStatement = $this->connection->prepare($gradOrd);

            $signStat = "SELECT name, surname, fn FROM student ORDER BY name ASC, surname ASC";
            $this->signatureStatement = $this->connection->prepare($signStat);

            $notRetToga = "SELECT name, surname, student.fn FROM participants JOIN student ON participants.fn = student.fn WHERE togaReturned is NULL ORDER BY name ASC, surname ASC";
            $this->togaNotReturnedStatement = $this->connection->prepare($notRetToga);

            $hatLst = "SELECT name, surname, student.fn FROM participants JOIN student on participants.fn = student.fn WHERE hat = 1 ORDER BY name ASC, surname ASC";
            $this->hatListStatement = $this->connection->prepare($hatLst);

            $admInfo = "SELECT name, surname FROM administration WHERE username = :username";
            $this->returnAdmStatement = $this->connection->prepare($admInfo);

            $awdStudent = "UPDATE participants SET prize = :prize WHERE fn = :fn";
            $this->awardStatement = $this->connection->prepare($awdStudent);

            $checkParticip = "SELECT * FROM participants WHERE fn = :fn";
            $this->checkParticipationStatement = $this->connection->prepare($checkParticip);

            $returnedToga = "UPDATE participants SET togaReturned = 1 WHERE fn = :fn";
            $this->returnedTogaStatement = $this->connection->prepare($returnedToga);

            $togaTaken = "SELECT name, surname, student.fn, toga FROM student JOIN participants on student.fn = participants.fn ORDER BY name ASC, surname ASC";
            $this->togaTakenStatement = $this->connection->prepare($togaTaken);

            $speechStud = "SELECT name, surname, student.fn FROM participants JOIN student on participants.fn = student.fn WHERE speech = 1 ORDER BY mark DESC LIMIT 3";
            $this->speechSelectedStatement = $this->connection->prepare($speechStud);

            $exlStud = "SELECT name, surname, fn, mark FROM student WHERE mark >= 5.50 ORDER BY mark DESC";
            $this->excellentStatement = $this->connection->prepare($exlStud);
        }

        // role should be student or administration
        public function insertUser($data, $role) { 
            try {
                $userData = ["username" => $data["username"], "password" => $data["password"], "role" => $role];
                $this->insertUserStatement->execute($userData);

                return ["success" => true];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function insertStudent($data) {
            try {
                $hashedPassword = password_hash($data["password"], PASSWORD_DEFAULT);
                $data["password"] = $hashedPassword;
                $res = $this->insertUser($data, "student");

                // if it is true, then we have added the user successfully, else we have not added him/her
                if ($res["success"] == true) {
                    unset($data["password"]);
                    $this->insertStudentStatement->execute($data);
                    return ["success" => true];
                }
                else {
                    return ["success" => false, "error" => "Въведеното потребителско име вече съществува."];
                }
            } catch(PDOException $e) {
                $this->deleteUserStatement->execute(["username" => $data["username"]]);
                return ["success" => false, "error" => "Вече съществува потребител с въведения факултетен номер."];
            }
        }

        public function insertStudentInDB($data) {
            try {
                $this->findMarkStatement->execute(["fn" => $data["fn"]]);
                $mark = $this->findMarkStatement->fetch();

                if ($mark == false) {
                    return ["success" => false, "error" => "Вашият факултетен номер не фигурира в списъка със завършващи студенти."];
                }
                else { // he/she is in the graduation list
                    $res = $this->insertStudent($data);

                    if ($res["success"] == true) {
                        $this->updateStudentMark($data["fn"], $mark["gradMark"]);
                        return ["success" => true];
                    }
                    else {
                        return $res;
                    }
                }
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function updateStudentMark($facultyNumber, $mark) { 
            try {
                $this->updateMarkStatement->execute(["mark" => $mark, "fn" => $facultyNumber]);
                
                return ["success" => true]; 
            } catch(PDOExcecption $e) {
                return ["sucess" => false, "error" => $e->getMessage()];
            }
        }

        public function insertAdministration($data) {
            try {
                $hashedPassword = password_hash($data["password"], PASSWORD_DEFAULT);
                $data["password"] = $hashedPassword;
                $res = $this->insertUser($data, "administration");

                if ($res["success"] == true) {
                    unset($data["password"]);
                    $this->insertAdministrationStatement->execute($data);
                    return ["success" => true];
                }
                else {
                    return ["success" => false, "error" => "Въведеното потребителско име вече съществува."];
                }
            } catch (PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function checkLogin($data) {
            try {
                $this->selectUserStatement->execute(["username" => $data["username"]]);
                $user = $this->selectUserStatement->fetch();

                if ($user == false) {
                    return ["success" => false, "error" => "Въведено е несъщестуващо потребителско име!"];
                }

                if (!password_verify($data["password"], $user["password"])) {
                    return ["success" => false, "error" => "Въведена е неправилна парола!"];
                }

                if ($user["role"] === "student"){
                    return ["success" => true, "role" => "student"];
                }
                else if ($user["role"] === "administration"){
                    return ["success" => true, "role" => "administration"];
                }
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function fillParticipation($username, $value) {
            try {
                $this->participationStatement->execute(["username" => $username, "val" => $value]);
                return ["success" => true];

            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function returnStudent($username) {
            try {
                $this->returnStudentStatement->execute(["username" => $username]);
                $user = $this->returnStudentStatement->fetch();

                return ["success" => true, "data" => $user];
            }catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function insertParticipant($fn) {
            try {
                $this->insertParticipantStatement->execute(["fn" => $fn]);
                return ["success" => true];

            } catch(PDOException $e) {
                return ["sucess" => false, "error" => $e->getMessage()];
            }
        }

        public function insertExtraInfo($data) {
            try {
                $this->insertExtraInfoStatement->execute($data);

                return ["success" => true];
            } catch (PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function selectExtraInfo($username) {
            try {
                $this->returnExtraInfoStatement->execute(["username" => $username]);
                $user = $this->returnExtraInfoStatement->fetch();

                return ["success" => true, "data" => $user];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function selectAwardedStudents() {
            try {
                $this->returnAwardedStatement->execute();
                $awdStudents = $this->returnAwardedStatement->fetchAll();

                return ["success" => true, "data" => $awdStudents];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function totalAttendance() {
            try {
                $this->totalAttendanceStatement->execute();
                $totalNum = $this->totalAttendanceStatement->fetch();

                return ["success" => true, "data" => $totalNum["total places"]];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function studentOrder() {
            try {
                $this->graduationOrderStatement->execute();
                $gradOrder = $this->graduationOrderStatement->fetchAll();

                return ["success" => true, "data" => $gradOrder];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function togaNotReturned() {
            try {
                $this->togaNotReturnedStatement->execute();
                $listNotRet = $this->togaNotReturnedStatement->fetchAll();

                return ["success" => true, "data" => $listNotRet];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function hatList() {
            try {
                $this->hatListStatement->execute();
                $studHatList = $this->hatListStatement->fetchAll();

                return ["success" => true, "data" => $studHatList];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function studentSpeeches() {
            try {
                $this->speechSelectedStatement->execute();
                $res = $this->speechSelectedStatement->fetchAll();

                return ["success" => true, "data" => $res];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function returnAdministration($username) {
            try {
                $this->returnAdmStatement->execute(["username" => $username]);
                $user = $this->returnAdmStatement->fetch();

                return ["success" => true, "data" => $user];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function checkParticipation($fn){
            try {
                $this->checkParticipationStatement->execute(["fn" => $fn]);
                $user = $this->checkParticipationStatement->fetch();

                if ($user == false) {
                    return ["success" => true, "participate" => false, "message" => "Въведеният ФН няма да участва."];
                }
                else {
                    return ["success" => true, "participate" => true];
                }

            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function giveAward($fn, $prize) {
            try {
                $this->awardStatement->execute(["fn" => $fn, "prize" => $prize]);

                return ["success" => true];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function markTogaAsReturned($fn) {
            try {
                $this->returnedTogaStatement->execute(["fn" => $fn]);

                return ["success" => true];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function participStat() {
            try {
                $this->participationStatStatement->execute();
                $res = $this->participationStatStatement->fetch();

                $data = ["all" => $res["all"], "attend" => $res["attend"], "miss" => $res["all"] - $res["attend"]];

                return ["success" => true, "data" => $data];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function togaTaken() {
            try {
                $this->togaTakenStatement->execute();
                $togaTakenList = $this->togaTakenStatement->fetchAll();

                return ["success" => true, "data" => $togaTakenList];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function signStudents() {
            try {
                $this->signatureStatement->execute();
                $signStud = $this->signatureStatement->fetchAll();

                return ["success" => true, "data" => $signStud];

            } catch(PDOException $e) { 
                return ["success" => false, "error" => $e->getMessage()];
            }
        }

        public function excellentStud() {
            try {
                $this->excellentStatement->execute();
                $res = $this->excellentStatement->fetchAll();

                return ["success" => true, "data" => $res];
            } catch(PDOException $e) {
                return ["success" => false, "error" => $e->getMessage()];
            }
        }
    }
?>