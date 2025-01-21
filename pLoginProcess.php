<?php
require_once "../pDatabase.php";

define('REQUIRE_ERROR', 'THIS FIELD IS REQUIRED');

$error = [];
$username = $password = "";

function storeData($data)
{
    if(isset($data))
    {
        return $data;
    }
    else
    {
        return false;
    }
}

if($_SERVER['REQUEST_METHOD'] === "POST")
{
    if(isset($_POST['vendorLogin']))
    {
        $username = storeData($_POST['username']);
        $password = storeData($_POST['password']);

        if(!$username)
        {
            $error['username'] = REQUIRE_ERROR;
        }

        if(!$password)
        {
            $error['password'] = REQUIRE_ERROR;
        }

        if($username && $password)
        {
            $sql = "SELECT * FROM vendor WHERE (Username = ? OR Email = ?) AND Password = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $rowCount = $result->num_rows;

            if($rowCount > 0) // one basically
            {
                $row = $result->fetch_assoc();
                session_start();
                $_SESSION['vendor_id'] = $row['VendorID'];
                $_SESSION['username'] = $row['Username'];
                header("location: ./tabletest.php");
            }
            else
            {
                $sql = "SELECT * FROM vendor WHERE Username = ? OR Email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $username, $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $rowCount = $result->num_rows;

                if($rowCount > 0)
                {
                    $error['password'] = "WRONG PASSWORD";
                }
                else
                {
                    $error['username'] = "WRONG USERNAME";
                }
            }
            $stmt->close();
        }
    }
}
?>
