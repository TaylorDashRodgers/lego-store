<?php
include('Unit6_database.php');

if (isset($_GET['input']) && isset($_GET['searchBy'])) {
    $input = '%' . $_GET['input'] . '%'; // Add wildcards for partial matching
    $searchBy = $_GET['searchBy'];

    // Call the function to get customer suggestions from the database
    $suggestions = getCustomerSuggestions(getConnection(), $input, $searchBy);

    if ($suggestions) {
        // Return the suggestions as JSON
        echo json_encode($suggestions);
    } else {
        // Return an appropriate message if no suggestions are found
        echo json_encode(['message' => 'No matching customers found']);
    }
}
?>