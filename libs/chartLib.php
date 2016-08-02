<?php
//===============================================================================
// Name        : chartLib.php
// Author      : Lucas Woltmann
// Version     : 1.0
// Date        : 08-2013
// Description : Provides functions for creating beautiful and astonishing charts
//===============================================================================

/**
 * Draws x- and y-axis onto image.
 *
 * @param image $image The image to draw on.
 * @param integer $XLength Max x value.
 * @param integer $YLength Max y value.
 * @param array $values Captions for y-axis.
 */
function DrawCoords($image, $XLength, $YLength, $values)
{
  $width = ImageSX($image);
  $height = ImageSY($image);
  
  //scale offset for each axis, minimize Xoffset for small values of Xlength
  $Xoffset = 0.8*($width/$XLength);
  $Yoffset = 0.8*($height/$YLength);
  
  if($Xoffset>150)
  {
    $Xoffset=$Xoffset/10;
  }
  
  //lines out of image
  if($width < ($XLength * $Xoffset) || $height < ($YLength * $Yoffset))
  {
    echo "lines out of boundaries";
  }
  
  $black = ImageColorAllocate($image, 0, 0, 0);
  
  
  $left = ($width - $XLength * $Xoffset)/2;
  $right = $width-$left;
  $upper = ($YLength * $Yoffset)+$Yoffset;
  $YSpace = ($upper - $Yoffset)/$YLength;
  $XSpace = ($right - $left)/$XLength;
  
  //draws X-Axis
  ImageLine($image, $left, $upper, $right, $upper, $black);
  
  //draws y-Axis with Yoffset as upper margin
  ImageLine($image, $left, $upper,$left, $Yoffset,$black);
  
  //draws arrows
  ImageLine($image, $right-5, $upper-5,$right,$upper,$black);
  ImageLine($image, $right-5, $upper+5,$right,$upper,$black);
  ImageLine($image, $left, $Yoffset,$left+5,$Yoffset+5,$black);
  ImageLine($image, $left, $Yoffset,$left-5,$Yoffset+5,$black);
  
  //draws lines for measurement at the y-Axis
  for($i=1;$i<$YLength;$i++)
  {
    ImageLine($image, $left-4, $upper-$YSpace*$i ,$left+4, $upper-$YSpace*$i,$black);
  }
  
  //same for the x-Axis
  for($j=1;$j<$XLength;$j++)
  {
    ImageLine($image, $left+$XSpace*$j, $upper-4 ,$left+$XSpace*$j, $upper+4,$black);
  }
  
  //caption for x-Axis
  $index=0;
  while($index < $XLength)
  {
    if($index % 5 == 0)
    {
      ImageString($image, 4, $left+($XSpace*$index)-($Xoffset/4), $upper+($Yoffset/3), $index, $black);
    }
    $index++;
  }
  
  //caption for y-Axis
  $index=0;
  while($index < count($values))
  {
    ImageString($image, 4, $left-(0*$Xoffset+30), $upper-$YSpace*$index-($Yoffset*0.7), $values[$index], $black);
    $index++;
  }
}
/**
 * Draws a bar onto the image.
 *
 * @param image $image The image to draw on.
 * @param integer $length Value of the bar.
 * @param integer $maxOfX Max x value.
 * @param integer $position Index of the column on the axis.
 * @param integer $maxOfY Max y value.
 * @param string $value Label for the bar.
 * @param color $color Well, have a guess!
 */
function DrawBar($image, $length, $maxOfX, $position, $maxOfY, $value, $color)
{
  $imgWidth = ImageSX($image);
  $imgHeight = ImageSY($image);
  
  //scale offset for each axis and minimize Xoffset for small values of maxOfX
  $Xoffset = 0.8*($imgWidth/$maxOfX);
  $Yoffset = 0.8*($imgHeight/$maxOfY);
  
  if($Xoffset>150)
  {
    $Xoffset=$Xoffset/10;
  }
  
  $length = $length*$Xoffset;
  $black = ImageColorAllocate($image, 0, 0, 0);
  
  $left = ($imgWidth - $maxOfX * $Xoffset)/2;
  $upper = /*($maxOfY*$Yoffset) +*/ $Yoffset + ($position*$Yoffset);
  $right = $left+$length;
  ImageFilledRectangle($image, $left, $upper, $right, $upper-$Yoffset, $color);
  ImageString($image, 4, $right+($Xoffset/3), $upper-$Yoffset*0.7, $value, $black);
}
/**
 * Creates and returns a new blank image.
 *
 * @param integer $width The desired width of the image.
 * @param integer $height The desired height of the image.
 */
function CreateImage($width, $height)
{
  $image = ImageCreate($width, $height);
  $white = ImageColorAllocateAlpha($image, 255, 255, 255, 127);
  
  ImageFill($image, 0, 0, $white);
  ImageSaveAlpha($image, TRUE);
  
  return $image;
}
/**
 * Creates a new bar plot for the given question data. Returns name of saved png file.
 *
 * @param integer $width The desired width of the image.
 * @param integer $height The desired height of the image.
 * @param array $question The question data.
 */
function CreateQuestionBar($width, $height, $question)
{
  //find max of answers to set max of x-axis, max of y-axis is always seven, because there are six possibilities to answer
  $values = $question;
  array_shift($values);
  $maxX = max($values)+1;
  $maxY = 7;
  
  $img = CreateImage($width, $height);

  // the amount of answers for the different options and a nice group of bars
  for ($i = 1; $i < 7; $i++)
  {
    echo "      <div class=\"col-2\"><p class=\"lead center\">" . $question[$i] . "</p></div>\n";
    $green = 150*($question[$i]/$maxX) + 40;
    $color = ImageColorAllocate($img, 0.57 * $green, $green, 0.45 * $green);
    DrawBar($img, $question[$i], $maxX, $i+1, $maxY,  $question[$i], $color);
  }
  
  //finish image and save it
  $caption = array("N/A","--","-", "0", "+", "++");
  DrawCoords($img, $maxX, $maxY, $caption);
  
  $file = str_replace("?", "", str_replace(" ", "", $question[0]));
  $file = "question".$file.".png";
  
  ImagePNG($img, $file);
  ImageDestroy($img);

  return $file;
}
/**
 * Creates a new bar plot for the given tutor data. Returns name of saved png file.
 *
 * @param integer $width The desired width of the image.
 * @param integer $height The desired height of the image.
 * @param string $title The file name of the image. (Should be tutor's name)
 * @param array $tutor The tutor data.
 */
function CreateTutorBar($width, $height, $title, $tutor)
{
  //find max of answers to set max of x-axis, max of y-axis is always seven, because there are six possibilities to answer
  $maxX = max($tutor)+1;
  $maxY = 7;
  
  $img = CreateImage($width, $height);
  
  // the amount of answers for the different options and a picture 
  for ($i = 0; $i < 6; $i++)
  {
    echo "      <div class=\"col-2\"><p class=\"lead center\">" . $tutor[$i] . "</p></div>\n";
    $green = 150*($tutor[$i]/$maxX) + 40;
    $color = ImageColorAllocate($img, 0.54 * $green, $green, 0.45 * $green);
    DrawBar($img, $tutor[$i], $maxX,  $i+2, $maxY,  $tutor[$i], $color);
  }
  
  //finish image and save it
  $caption = array("N/A","--","-", "0", "+", "++");
  DrawCoords($img, $maxX, $maxY, $caption);
  
  $file = str_replace(" ", "", $title);
  $file = "tutor".$file.".png";

  ImagePNG($img, $file);
  ImageDestroy($img);

  return $file;
}
?>
