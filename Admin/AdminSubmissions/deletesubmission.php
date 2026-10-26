<?php
include "../Components/checkifadmin.php";
include "../../Config/db.php";

require_csrf_token();

if (isset($_POST['id'])) {
    $submissionID = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM submissions WHERE SubmissionID = ?");
    $stmt->bind_param("i", $submissionID);

    if ($stmt->execute()) {
        header("Location: submisions.php?success=Submission deleted.");
        exit();
    } else {
        echo "Failed to delete submission.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
