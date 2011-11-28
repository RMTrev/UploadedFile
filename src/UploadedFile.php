<?php

class UploadedFile
{
  function __construct($name)
  {
    if(isset($_FILES[$name]))
      {
	switch($_FILES[$name]['error'])
	  {
	  case 4:
	    $this->uploaded=false;
	    break;

	  case 0:
	    $this->uploaded=true;
	    $this->tempname=$_FILES[$name]['tmp_name'];
	    $this->actualname=$_FILES[$name]['name'];
	    $this->size=$_FILES[$name]['size'];
	    $this->type=$_FILES[$name]['type'];
	    break;

	  case 1:
	    throw new UploadException(1,"File size exceeds limit defined in the configuration.");
	    break;

	  case 2:
	    throw new UploadException(2,"File size exceeds limit defined in the form.");
	    break;

	  case 3:
	    throw new UploadException(3,"File was partially uploaded.");
	    break;

	  case 6:
	    throw new UploadException(6,"Temporary folder missing.");
	    break;

	  case 7:
	    throw new UploadException(7,"Failed to write file to disk.");
	    break;

	  case 8:
	    throw new UploadException(8,"File upload stopped by extension.");
	    break;
	  }
      }
    else
      {
	$this->uploaded=false;
      }
  }

  public function version()
  {
    return "1.0.1";
  }

  public function isUploaded()
  {
    return $this->uploaded;
  }

  public function getName()
  {
    if($this->uploaded)
       return $this->actualname;
     else
       return null;
  }

  public function getSize()
  {
    if($this->uploaded)
      return $this->size;
    else
      return null;
  }

  public function getType()
  {
    if($this->uploaded)
      return $this->type;
    else
      return null;
  }

  public function getFileData()
  {
    if($this->uploaded)
      {
	$buf=file_get_contents($this->tempname);
	if($buf===false)
	  {
	    throw new Exception("Error reading file.");
	  }

	return $buf;
      }
    else
      return null;
  }

  private $uploaded;
  private $tempname;
  private $actualname;
  private $size;
}

class UploadException extends Exception
{
  function __construct($errcode, $msg)
  {
    $this->errcode=$errcode;

    parent::__construct($msg);
  }

  public function getErrorCode()
  {
    return $this->errcode;
  }

  private $errcode;
}

?>
