<?php
//============================================================================
// Name        : chartLib.php
// Author      : Lucas Woltmann
// Version     : 1.0
// Date        : 08-2013
// Description : Provides functions for creating beautiful and astonishing charts
//============================================================================

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

function DrawBar($image, $length, $maxOfX ,$position, $maxOfY, $value, $color)
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

function CreateImage($width, $height)
{
  $image = ImageCreate($width, $height);
  $white = ImageColorAllocateAlpha($image, 255, 255, 255, 127);
  
  ImageFill($image, 0, 0, $white);
  ImageSaveAlpha($image, TRUE);
  
  return $image;
}
?>