<?php
include (__DIR__ . '/dbpointer.php');

function getAllArt()
{
    global $db;

    $results = [];

    $sqlString = $db->prepare("SELECT artid, userid, artname, art FROM paintart");

    if($sqlString->execute() && $sqlString->rowCount() > 0)
    {
        $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
    }
    return ($results);
}

function searchArt ($userid) {
    global $db;
    $binds = array();

    $sql =  "SELECT * FROM paintart WHERE userid = :id";

    $binds['id'] = $userid;

    $results = array();

    $stmt = $db->prepare($sql);

    if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
         
    return ($results);
}

function addArt($userid, $artname, $art)
{
    global $db;
    $results = '';

    $sqlString = $db->prepare("INSERT INTO paintart SET userid = :u, artname = :n, art = :a");

    $binds = array(
        ":u" => $userid,
        ":n" => $artname,
        ":a" => $art
    );

    if($sqlString->execute($binds) && $sqlString->rowCount() > 0)
    {
        $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
    }

    return ($results);
}

function getArt($id) 
{
    global $db;

    $results = [];

    // Preparing SQL query 
    //    id is used to ensure we delete correct record
    $sqlString = $db->prepare("SELECT artid, userid, artname, art FROM paintart WHERE id=:idParam");

     // Bind query parameter to method parameter value
     $sqlString->bindValue(':idParam', $id);
   
     // Execute query and check to see if rows were returned 
     if ( $sqlString->execute() && $sqlString->rowCount() > 0 ) 
     {
        // if successful, grab the first row returned
        $results = $sqlString->setFetchMode(PDO::FETCH_ASSOC);
        $results = $sqlString->fetch();                       
    }

    // Return results to client
    return $results;
}

function updateArt($id, $userid, $art)
{
    global $db;

    $updateSucessful = false;

    $sqlString = $db->prepare("UPDATE paintart SET userid = :userIdParam, artname = :artNameParam, art = :artParam WHERE id=:idParam");
    
    $sqlString->bindValue(':idParam', $id);
    $sqlString->bindValue(':userIdParam', $userid);
    $sqlString->bindValue(':artNameParam', $userid);
    $sqlString->bindValue(':artParam', $art);

    $updateSucessful = ($sqlString->execute() && $sqlString->rowCount() > 0);

    return $updateSucessful;
}

function deleteArt($id) 
{
    global $db;

    $deleteSucessful = false;

    $sqlString = $db->prepare("DELETE FROM paintart WHERE id=:idParam");
    
        // Bind query parameter to method parameter value
    $sqlString->bindValue(':idParam', $id);
        
    // Execute query and check to see if rows were returned 
    // If so, the team was successfully deleted      
    $deleteSucessful = ($sqlString->execute() && $sqlString->rowCount() > 0);

    // Return status to client           
    return $deleteSucessful;
}

function login($user, $pass){
    global $db;
    
    $result = [];
    $stmt = $db->prepare("SELECT * FROM paintusers WHERE userName=:user AND userPassword=sha1(:pass)");
    $stmt->bindValue(':user', $user);
    $stmt->bindValue(':pass', $pass);
   
    if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
         $result = $stmt->fetch(PDO::FETCH_ASSOC);
     }
     
     return ($result);
}

function searchUser($id){
    global $db;
    
    $result = [];
    $stmt = $db->prepare("SELECT userName FROM paintusers WHERE userid=:id");
    $stmt->bindValue(':id', $id);
   
    if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
         $result = $stmt->fetch(PDO::FETCH_ASSOC);
     }
     
     return ($result);
}

?>