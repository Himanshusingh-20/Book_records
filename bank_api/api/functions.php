<?php

//..to add new customer's transaction or update exisiting......................................................................................................................
function add_transaction($conn, $data)
{
    $item = [];
    if (empty($data['id'])) {
        $insertBalance = "INSERT INTO accounts (customer_name, cash_transactions, total_amount) VALUES ('" . $data['user'] . "','+" . $data['deposit'] . "','" . $data['mybalance'] . "')";
        // print_r($insertBalance);die();
        $accountResult = mysqli_query($conn, $insertBalance);
        $inserted_id = mysqli_insert_id($conn);
    } else {
        $updatebalance = " UPDATE accounts SET total_amount='" . $data['mybalance'] . "' WHERE  id='" . $data['id'] . "'";
        $result = mysqli_query($conn, $updatebalance);
        $inserted_id = $data['id'];
    }

    $sql = 'SELECT * FROM `accounts` where id="' . $inserted_id . '"';
    $result = mysqli_query($conn, $sql);
    $numrows = mysqli_num_rows($result);

    if ($numrows > 0) {
        $acc = mysqli_fetch_array($result);
        $item['accounts'] = $acc;
    }
    return $item;
}



//..to display customer names on bankers page and on click of the name display transactions................................................................................
function get_customers($conn){

    $selectQuery = "SELECT * FROM users ";
    $item['users'] = [];
    $result = mysqli_query($conn, $selectQuery);
    $numrows = mysqli_num_rows($result);
    
    if( $numrows > 0 ){

        while ($user = mysqli_fetch_array($result)) {
            $item['users'][] = $user;
        }

    }else{

        return json_encode("There are no customers.");
    }
    
    return $item;
}



//..To get selected person's transaction list......(here i am not understanding how to specify id to fetch transactions)................................................................................................................
function get_customersTransaction($conn, $data){

    $selectQuery = "SELECT `cash_transactions` FROM `accounts` WHERE id='" . $data['id'] . "'";
    $item['accounts'] = [];
    $result = mysqli_query($conn, $selectQuery);
    $numrows = mysqli_num_rows($result);
    
    if( $numrows > 0 ){

        $user = mysqli_fetch_array($result);
        // $item['accounts'][50] = $user;

    }else{

        return json_encode("There are no Transactions.");
    }
    
    return $user;

}



//..to display the acccounts table ..................................................................................................................
function get_accounts($conn)
{
    $sql = 'SELECT * FROM `accounts`';
    $item['accounts'] = [];
    $result = mysqli_query($conn, $sql);
    $numrows = mysqli_num_rows($result);

    if ($numrows > 0) {
        while ($acc = mysqli_fetch_array($result)) {
            $item['accounts'][] = $acc;
        }
    }
    // $data_file = __DIR__ . '/../data/data.json';
    // $data = file_get_contents($data_file);
    // print_r($item);die();
    return $item;
}



//..to add new user or update the existing one into the user table............................................................................................................................................................................. 
function add_user($conn, $data)
{
    if (empty($data['id'])) {
        $item = [];
        $insertUser = "INSERT INTO `users`(`username`, `password`, `banker`, `balance`) VALUES ('" . $data['username'] . "','" . $data['password'] . "','" . $data['banker'] . "','" . $data['balance'] . "')";
        // echo $insertUser; die();
        // print_r($insertUser);die();
        $result = mysqli_query($conn, $insertUser);
        $inserted_id = mysqli_insert_id($conn);
        $item['user_id'] = $inserted_id;
        
    }else{

        $updatebalance = " UPDATE users SET username='" . $data['username'] . "', password='" . $data['password'] . "', banker='" . $data['banker'] . "', balance='" . $data['balance'] . "' WHERE  id='" . $data['id'] . "'";
        $result = mysqli_query($conn, $updatebalance);
        $inserted_id = $data['id'];

    }

        $item['users'] = [];
        $sql = 'SELECT * FROM `users` where id="' . $inserted_id . '"';
        $result = mysqli_query($conn, $sql);
        $numrows = mysqli_num_rows($result);
        if ($numrows > 0) {
            $acc = mysqli_fetch_array($result);
            $item['users'] = $acc;
        }
    return $item;
}








function get_books()
{
    // print($conn);die();
    $data_file = __DIR__ . '/../data/data.json';
    $data = file_get_contents($data_file);
    // print_r($data);die();
    return json_decode($data, true);
}

function get_book($id)
{
    $books = get_books();
  
    if (!empty($books)) {
        foreach ($books as $row) {
            if ($row['book_id'] == $id) {
                return $row;
            }
        }
        // print_r($books['book_id'][$id]);die();
        // return $books[$id];
    }
    return null;
}

function add_book($book)
{
    // print_r($book);die();
    $books = get_books();
    $books[] = $book;
    // print_r($books);die();
    save_books($books);
}

function update_book($id, $book)
{
    $books = get_books();
    foreach ($books as &$b) {
        if ($b['book_id'] == $id) {
            $b = array_merge($b, $book); // Merge updated data into existing book by me
            save_books($books);
            return true;
        }
    }
    return false; // Book with specified ID not found
}

function delete_book($id)
{
    $books = get_books();
    if (!empty($books)) {
        $i = 0;
        for($i=0; $i<count($books);$i++){
           
            if($books[$i]['book_id'] == $id){
                echo $i;
                break;
                // print_r($books[$i]['book_id']);
                // die();
            }
        }
        unset($books[$i]);
        $books = array_values($books);
        save_books($books);
        return $books; 
    }
    return $books;
}

function save_books($books)
{
    $data_file = __DIR__ . '/../data/data.json';
    // print_r($data_file);
    $data = json_encode($books);
    file_put_contents($data_file, $data);
}
