<?php
include_once "../includes/application.php";
include_once "../includes/header.php";

$Application = $GLOBALS['APP'];

echo "<h2><a href=\"index.php?p=courses\">Courses</a></h2>";
echo "<p><a href=\"index.php?p=courses&create=1\">New course</a></p>";

// handle form POST
if (isset($_POST['id']))
{
  $id = intval($_POST['id']);
  $courseName = $Application->EscapeString($_POST['name']);
  $courseDesc = $Application->EscapeString($_POST['description']);
  $courseTime = $Application->EscapeString($_POST['time']);
  $coursePrice = floatval($_POST['price']);
  if ($id == 0) // create new record
  {
    $Application->CreateCourse($courseName, $courseDesc, $courseTime, $coursePrice);
  }
  else // edit record
  {
    $Application->UpdateCourse($id, $courseName, $courseDesc, $courseTime, $coursePrice);
  }
}
if (isset($_GET['delete']))
{
  $id = intval($_GET['delete']);
  $Application->DeleteCourse($id);
  header("index.php?p=courses");
} 
if (isset($_GET['create']) || isset($_GET['edit']))
{
  // Default values
  $courseName = "";
  $courseDesc = "";
  $courseTime = "2022-12-04 10:00:00";
  $coursePrice = 0;
  $id = 0;
  $action = 0;
  $actionName = "New course";
  $actionButton = "Create";
  if (isset($_GET['create'])) // create new
    $action = 0;
  else // edit 
  {
    $action = 1;
    $actionName = "Edit course";
    $actionButton = "Edit";
    $id = intval($_GET['edit']);
    $coursesResult = $Application->GetCourse($id);
    if ($row = $coursesResult->fetch_assoc()) 
    {
      $courseName = $row['name'];
      $courseDesc = $row['description'];
      $courseTime = $row['time'];
      $coursePrice = $row['price'];
    }
  }
  echo '
        <form action="index.php?p=courses" method="post">
        <input type="hidden" name="id" value="'.$id.'">
          <fieldset> <legend><b>'.$actionName.'</b></legend>
          <table> 
            <tr><td>Name</td><td> <input name="name" size="30" tabindex="1" type="text" value="'.$courseName.'"/></td></tr>
            <tr><td>Time</td><td> <input name="time" size="30" tabindex="2" type="text" value="'.$courseTime.'"/> Format: YYYY-MM-DD HH:MM:SS <small>There is no format control implemented yet so just be careful :)</small></td></tr>
            <tr><td>Price</td><td> <input name="price" size="30" tabindex="3" type="number" step="0.01" value="'.$coursePrice.'"/></td></tr>
            <tr><td>Description</td><td> <textarea tabindex="4" name="description" rows="4" cols="50">'.$courseDesc.'</textarea></td></tr>       
            <tr><td></td><td><input name="submit" type="submit" tabindex="5" value="'.$actionButton.'" /></td></tr> 
            </table>
          </fieldset> 
        </form>
      '; 
}
else 
{
  $coursesResult = $Application->GetCourses();
  echo "<ul>";
  while ($row = $coursesResult->fetch_assoc()) {
    echo "<li>[<a href=\"index.php?p=courses&delete=" . $row['id'] . "\" onclick=\"return confirm('Do you really want to delete this item?');\"><small>x</small></a>]
    <a href=\"index.php?p=courses&edit=" . $row['id'] . "\">" . $row['name'] . "</a></li>";
  }
  echo "</ul>";
}
?>