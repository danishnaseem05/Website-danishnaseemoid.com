<?php

// Copyright Softaculous Ltd.

if (!defined('ABSPATH')){
    exit;
}

if(!class_exists('PclZip')){
	include_once(ABSPATH.'wp-admin/includes/class-pclzip.php');
}

class softpclzip extends PclZip{

	function __construct($p_zipname){
		return parent::PclZip($p_zipname);
	}
	
	function restore_list(){
		$v_result=1;

		// ----- Reset the error handler
		$this->privErrorReset();
		
		// ----- Check archive
		if (!$this->privCheckFormat()) {
			return(0);
		}
		
		// ----- Call the extracting fct
		$p_list = array();		
		
		// ----- Magic quotes trick
		$this->privDisableMagicQuotes();
		
		// ----- Open the zip file
		if (($this->zip_fd = @fopen($this->zipname, 'rb')) == 0)
		{
		  // ----- Magic quotes trick
		  $this->privSwapBackMagicQuotes();
		  
		  // ----- Error log
		  PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open archive \''.$this->zipname.'\' in binary read mode');
		
		  // ----- Return
		  return PclZip::errorCode();
		}
		
		// ----- Read the central directory informations
		if (($v_result = $this->restore_privReadEndCentralDir($p_list)) != 1)
		{
		  $this->privSwapBackMagicQuotes();
		  return $v_result;
		}

		// ----- Close the zip file
		$this->privCloseFd();
	
		// ----- Magic quotes trick
		$this->privSwapBackMagicQuotes();
		
		// ----- Return
		return $p_list;
	}
	
  function restore_privReadEndCentralDir(&$p_list){
    $v_result=1;
	
	$p_central_dir = array();
	
    // ----- Go to the end of the zip file
    $v_size = filesize($this->zipname);
    @fseek($this->zip_fd, $v_size);
    if (@ftell($this->zip_fd) != $v_size)
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to go to the end of the archive \''.$this->zipname.'\'');

      // ----- Return
      return PclZip::errorCode();
    }

    // ----- First try : look if this is an archive with no commentaries (most of the time)
    // in this case the end of central dir is at 22 bytes of the file end
    $v_found = 0;
    if ($v_size > 26) {
      @fseek($this->zip_fd, $v_size-22);
      if (($v_pos = @ftell($this->zip_fd)) != ($v_size-22))
      {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');

        // ----- Return
        return PclZip::errorCode();
      }

      // ----- Read for bytes
      $v_binary_data = @fread($this->zip_fd, 4);
      $v_data = @unpack('Vid', $v_binary_data);

      // ----- Check signature
      if ($v_data['id'] == 0x06054b50) {
        $v_found = 1;
      }

      $v_pos = ftell($this->zip_fd);
    }

    // ----- Go back to the maximum possible size of the Central Dir End Record
    if (!$v_found) {
      $v_maximum_size = 65557; // 0xFFFF + 22;
      if ($v_maximum_size > $v_size)
        $v_maximum_size = $v_size;
      @fseek($this->zip_fd, $v_size-$v_maximum_size);
      if (@ftell($this->zip_fd) != ($v_size-$v_maximum_size))
      {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');

        // ----- Return
        return PclZip::errorCode();
      }

      // ----- Read byte per byte in order to find the signature
      $v_pos = ftell($this->zip_fd);
      $v_bytes = 0x00000000;
      while ($v_pos < $v_size)
      {
        // ----- Read a byte
        $v_byte = @fread($this->zip_fd, 1);

        // -----  Add the byte
        //$v_bytes = ($v_bytes << 8) | Ord($v_byte);
        // Note we mask the old value down such that once shifted we can never end up with more than a 32bit number 
        // Otherwise on systems where we have 64bit integers the check below for the magic number will fail. 
        $v_bytes = ( ($v_bytes & 0xFFFFFF) << 8) | Ord($v_byte); 

        // ----- Compare the bytes
        if ($v_bytes == 0x504b0506)
        {
          $v_pos++;
          break;
        }

        $v_pos++;
      }

      // ----- Look if not found end of central dir
      if ($v_pos == $v_size)
      {

        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Unable to find End of Central Dir Record signature");

        // ----- Return
        return PclZip::errorCode();
      }
    }

    // ----- Read the first 18 bytes of the header
    $v_binary_data = fread($this->zip_fd, 18);

    // ----- Look for invalid block size
    if (strlen($v_binary_data) != 18)
    {

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid End of Central Dir Record size : ".strlen($v_binary_data));

      // ----- Return
      return PclZip::errorCode();
    }

    // ----- Extract the values
    $v_data = unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', $v_binary_data);

    // ----- Check the global size
    if (($v_pos + $v_data['comment_size'] + 18) != $v_size) {

	  // ----- Removed in release 2.2 see readme file
	  // The check of the file size is a little too strict.
	  // Some bugs where found when a zip is encrypted/decrypted with 'crypt'.
	  // While decrypted, zip has training 0 bytes
	  if (0) {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT,
	                       'The central dir is not at the end of the archive.'
						   .' Some trailing bytes exists after the archive.');

      // ----- Return
      return PclZip::errorCode();
	  }
    }

    // ----- Get comment
    if ($v_data['comment_size'] != 0) {
      $p_central_dir['comment'] = fread($this->zip_fd, $v_data['comment_size']);
    }
    else
      $p_central_dir['comment'] = '';

    $p_central_dir['entries'] = $v_data['entries'];
    $p_central_dir['disk_entries'] = $v_data['disk_entries'];
    $p_central_dir['offset'] = $v_data['offset'];
    $p_central_dir['size'] = $v_data['size'];
    $p_central_dir['disk'] = $v_data['disk'];
    $p_central_dir['disk_start'] = $v_data['disk_start'];
	
	//r_print($p_central_dir);
	
	 // ----- Read next Central dir entry
    @rewind($this->zip_fd);
    if (@fseek($this->zip_fd, $p_central_dir['offset'])){
		return $v_result; // Return the original result
	}
	
	//$start = microtime_float();
	
	for($i=1; $i<=200000; $i++){
		$binary_data = @fread($this->zip_fd, 4);
		$_data = unpack('Vid', $binary_data);
		if ($_data['id'] != 0x02014b50){
			if($_data['id'] == 0x06054b50){
				$total = $i - 1;
			}
			break;
		}
		
		fread($this->zip_fd, 24);
		
		$binary_data = fread($this->zip_fd, 6);
		$_header = unpack('vfilename_len/vextra_len/vcomment_len', $binary_data);
		
		fread($this->zip_fd, 12);
		
		// ----- Get filename
		if ($_header['filename_len'] != 0){
			$filename = fread($this->zip_fd, $_header['filename_len']);
			$vv = trim(trim($filename), '/');
			if(substr_count($vv, '/') || empty($vv)){
				
			}else{
				$p_list[] = $vv;
			}
		}
		
		// ----- Get extra
		if ($_header['extra_len'] != 0)
		fread($this->zip_fd, $_header['extra_len']);
		
		// ----- Get comment
		if ($_header['comment_len'] != 0)
		fread($this->zip_fd, $_header['comment_len']);
		
		unset($_header);
		
	}// End of for loop
	
	if(!empty($total)){
		$p_central_dir['entries'] = $total;
    	$p_central_dir['disk_entries'] = $total;
	}
	
    // TBC
    //for(reset($p_central_dir); $key = key($p_central_dir); next($p_central_dir)) {
    //}

    // ----- Return
    return $v_result;
  }
  
  // Less memory intensive EXTRACT
  function _extract()
  {
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Check archive
    if (!$this->privCheckFormat()) {
      return(0);
    }

    // ----- Set default values
    $v_options = array();
//    $v_path = "./";
    $v_path = '';
    $v_remove_path = "";
    $v_remove_all_path = false;

    // ----- Look for variable options arguments
    $v_size = func_num_args();

    // ----- Default values for option
    $v_options[PCLZIP_OPT_EXTRACT_AS_STRING] = FALSE;

    // ----- Look for arguments
    if ($v_size > 0) {
      // ----- Get the arguments
      $v_arg_list = func_get_args();

      // ----- Look for first arg
      if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {

        // ----- Parse the options
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                            array (PCLZIP_OPT_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
                                                   PCLZIP_OPT_ADD_PATH => 'optional',
                                                   PCLZIP_CB_PRE_EXTRACT => 'optional',
                                                   PCLZIP_CB_POST_EXTRACT => 'optional',
                                                   PCLZIP_OPT_SET_CHMOD => 'optional',
                                                   PCLZIP_OPT_BY_NAME => 'optional',
                                                   PCLZIP_OPT_BY_EREG => 'optional',
                                                   PCLZIP_OPT_BY_PREG => 'optional',
                                                   PCLZIP_OPT_BY_INDEX => 'optional',
                                                   PCLZIP_OPT_EXTRACT_AS_STRING => 'optional',
                                                   PCLZIP_OPT_EXTRACT_IN_OUTPUT => 'optional',
                                                   PCLZIP_OPT_REPLACE_NEWER => 'optional'
                                                   ,PCLZIP_OPT_STOP_ON_ERROR => 'optional'
                                                   ,PCLZIP_OPT_EXTRACT_DIR_RESTRICTION => 'optional',
                                                   PCLZIP_OPT_TEMP_FILE_THRESHOLD => 'optional',
                                                   PCLZIP_OPT_TEMP_FILE_ON => 'optional',
                                                   PCLZIP_OPT_TEMP_FILE_OFF => 'optional'
												    ));
        if ($v_result != 1) {
          return 0;
        }

        // ----- Set the arguments
        if (isset($v_options[PCLZIP_OPT_PATH])) {
          $v_path = $v_options[PCLZIP_OPT_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_REMOVE_PATH])) {
          $v_remove_path = $v_options[PCLZIP_OPT_REMOVE_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_REMOVE_ALL_PATH])) {
          $v_remove_all_path = $v_options[PCLZIP_OPT_REMOVE_ALL_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_ADD_PATH])) {
          // ----- Check for '/' in last path char
          if ((strlen($v_path) > 0) && (substr($v_path, -1) != '/')) {
            $v_path .= '/';
          }
          $v_path .= $v_options[PCLZIP_OPT_ADD_PATH];
        }
      }

      // ----- Look for 2 args
      // Here we need to support the first historic synopsis of the
      // method.
      else {

        // ----- Get the first argument
        $v_path = $v_arg_list[0];

        // ----- Look for the optional second argument
        if ($v_size == 2) {
          $v_remove_path = $v_arg_list[1];
        }
        else if ($v_size > 2) {
          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");

          // ----- Return
          return 0;
        }
      }
    }

    // ----- Look for default option values
    $this->privOptionDefaultThreshold($v_options);

    // ----- Trace

    // ----- Call the extracting fct
    $p_list = array();
    $v_result = $this->_privExtractByRule($p_list, $v_path, $v_remove_path,
	                                     $v_remove_all_path, $v_options);
    if ($v_result < 1) {
      unset($p_list);
      return(0);
    }

    // ----- Return
    return $p_list;
  }
  
  function _privExtractByRule(&$p_file_list, $p_path, $p_remove_path, $p_remove_all_path, &$p_options)
  {
    $v_result=1;

    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Check the path
    if (   ($p_path == "")
	    || (   (substr($p_path, 0, 1) != "/")
		    && (substr($p_path, 0, 3) != "../")
			&& (substr($p_path,1,2)!=":/")))
      $p_path = "./".$p_path;

    // ----- Reduce the path last (and duplicated) '/'
    if (($p_path != "./") && ($p_path != "/"))
    {
      // ----- Look for the path end '/'
      while (substr($p_path, -1) == "/")
      {
        $p_path = substr($p_path, 0, strlen($p_path)-1);
      }
    }

    // ----- Look for path to remove format (should end by /)
    if (($p_remove_path != "") && (substr($p_remove_path, -1) != '/'))
    {
      $p_remove_path .= '/';
    }
    $p_remove_path_size = strlen($p_remove_path);

    // ----- Open the zip file
    if (($v_result = $this->privOpenFd('rb')) != 1)
    {
      $this->privSwapBackMagicQuotes();
      return $v_result;
    }

    // ----- Read the central directory informations
    $v_central_dir = array();
    if (($v_result = $this->_privReadEndCentralDir($v_central_dir)) != 1)
    {
      // ----- Close the zip file
      $this->privCloseFd();
      $this->privSwapBackMagicQuotes();

      return $v_result;
    }

    // ----- Start at beginning of Central Dir
    $v_pos_entry = $v_central_dir['offset'];

    // ----- Read each entry
    $j_start = 0;
    for ($i=0, $v_nb_extracted=0; $i<$v_central_dir['entries']; $i++)
    {

      // ----- Read next Central dir entry
      @rewind($this->zip_fd);
      if (@fseek($this->zip_fd, $v_pos_entry))
      {
        // ----- Close the zip file
        $this->privCloseFd();
        $this->privSwapBackMagicQuotes();

        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');

        // ----- Return
        return PclZip::errorCode();
      }

      // ----- Read the file header
      $v_header = array();
      if (($v_result = $this->privReadCentralFileHeader($v_header)) != 1)
      {
        // ----- Close the zip file
        $this->privCloseFd();
        $this->privSwapBackMagicQuotes();

        return $v_result;
      }

      // ----- Store the index
      $v_header['index'] = $i;

      // ----- Store the file position
      $v_pos_entry = ftell($this->zip_fd);

      // ----- Look for the specific extract rules
      $v_extract = false;

      // ----- Look for extract by name rule
      if (   (isset($p_options[PCLZIP_OPT_BY_NAME]))
          && ($p_options[PCLZIP_OPT_BY_NAME] != 0)) {

          // ----- Look if the filename is in the list
          for ($j=0; ($j<sizeof($p_options[PCLZIP_OPT_BY_NAME])) && (!$v_extract); $j++) {

              // ----- Look for a directory
              if (substr($p_options[PCLZIP_OPT_BY_NAME][$j], -1) == "/") {

                  // ----- Look if the directory is in the filename path
                  if (   (strlen($v_header['stored_filename']) > strlen($p_options[PCLZIP_OPT_BY_NAME][$j]))
                      && (substr($v_header['stored_filename'], 0, strlen($p_options[PCLZIP_OPT_BY_NAME][$j])) == $p_options[PCLZIP_OPT_BY_NAME][$j])) {
                      $v_extract = true;
                  }
              }
              // ----- Look for a filename
              elseif ($v_header['stored_filename'] == $p_options[PCLZIP_OPT_BY_NAME][$j]) {
                  $v_extract = true;
              }
          }
      }

      // ----- Look for extract by ereg rule
      // ereg() is deprecated with PHP 5.3
      /* 
      else if (   (isset($p_options[PCLZIP_OPT_BY_EREG]))
               && ($p_options[PCLZIP_OPT_BY_EREG] != "")) {

          if (ereg($p_options[PCLZIP_OPT_BY_EREG], $v_header['stored_filename'])) {
              $v_extract = true;
          }
      }
      */

      // ----- Look for extract by preg rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_PREG]))
               && ($p_options[PCLZIP_OPT_BY_PREG] != "")) {

          if (preg_match($p_options[PCLZIP_OPT_BY_PREG], $v_header['stored_filename'])) {
              $v_extract = true;
          }
      }

      // ----- Look for extract by index rule
      else if (   (isset($p_options[PCLZIP_OPT_BY_INDEX]))
               && ($p_options[PCLZIP_OPT_BY_INDEX] != 0)) {
          
          // ----- Look if the index is in the list
          for ($j=$j_start; ($j<sizeof($p_options[PCLZIP_OPT_BY_INDEX])) && (!$v_extract); $j++) {

              if (($i>=$p_options[PCLZIP_OPT_BY_INDEX][$j]['start']) && ($i<=$p_options[PCLZIP_OPT_BY_INDEX][$j]['end'])) {
                  $v_extract = true;
              }
              if ($i>=$p_options[PCLZIP_OPT_BY_INDEX][$j]['end']) {
                  $j_start = $j+1;
              }

              if ($p_options[PCLZIP_OPT_BY_INDEX][$j]['start']>$i) {
                  break;
              }
          }
      }

      // ----- Look for no rule, which means extract all the archive
      else {
          $v_extract = true;
      }

	  // ----- Check compression method
	  if (   ($v_extract)
	      && (   ($v_header['compression'] != 8)
		      && ($v_header['compression'] != 0))) {
          $v_header['status'] = 'unsupported_compression';

          // ----- Look for PCLZIP_OPT_STOP_ON_ERROR
          if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
		      && ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {

              $this->privSwapBackMagicQuotes();
              
              PclZip::privErrorLog(PCLZIP_ERR_UNSUPPORTED_COMPRESSION,
			                       "Filename '".$v_header['stored_filename']."' is "
				  	    	  	   ."compressed by an unsupported compression "
				  	    	  	   ."method (".$v_header['compression'].") ");

              return PclZip::errorCode();
		  }
	  }
	  
	  // ----- Check encrypted files
	  if (($v_extract) && (($v_header['flag'] & 1) == 1)) {
          $v_header['status'] = 'unsupported_encryption';

          // ----- Look for PCLZIP_OPT_STOP_ON_ERROR
          if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
		      && ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {

              $this->privSwapBackMagicQuotes();

              PclZip::privErrorLog(PCLZIP_ERR_UNSUPPORTED_ENCRYPTION,
			                       "Unsupported encryption for "
				  	    	  	   ." filename '".$v_header['stored_filename']
								   ."'");

              return PclZip::errorCode();
		  }
    }

      // ----- Look for real extraction
      if (($v_extract) && ($v_header['status'] != 'ok')) {
          $v_result = $this->privConvertHeader2FileInfo($v_header,
		                                        $p_file_list[$v_nb_extracted++]);
          if ($v_result != 1) {
              $this->privCloseFd();
              $this->privSwapBackMagicQuotes();
              return $v_result;
          }

          $v_extract = false;
      }
      
      // ----- Look for real extraction
      if ($v_extract)
      {

        // ----- Go to the file position
        @rewind($this->zip_fd);
        if (@fseek($this->zip_fd, $v_header['offset']))
        {
          // ----- Close the zip file
          $this->privCloseFd();

          $this->privSwapBackMagicQuotes();

          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');

          // ----- Return
          return PclZip::errorCode();
        }

        // ----- Look for extraction as string
        if ($p_options[PCLZIP_OPT_EXTRACT_AS_STRING]) {

          $v_string = '';

          // ----- Extracting the file
          $v_result1 = $this->privExtractFileAsString($v_header, $v_string, $p_options);
          if ($v_result1 < 1) {
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();
            return $v_result1;
          }

          // ----- Get the only interesting attributes
          if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted])) != 1)
          {
            // ----- Close the zip file
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();

            return $v_result;
          }

          // ----- Set the file content
          $p_file_list[$v_nb_extracted]['content'] = $v_string;

          // ----- Next extracted file
          $v_nb_extracted++;
          
          // ----- Look for user callback abort
          if ($v_result1 == 2) {
          	break;
          }
        }
        // ----- Look for extraction in standard output
        elseif (   (isset($p_options[PCLZIP_OPT_EXTRACT_IN_OUTPUT]))
		        && ($p_options[PCLZIP_OPT_EXTRACT_IN_OUTPUT])) {
          // ----- Extracting the file in standard output
          $v_result1 = $this->privExtractFileInOutput($v_header, $p_options);
          if ($v_result1 < 1) {
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();
            return $v_result1;
          }

          // ----- Get the only interesting attributes
          if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted++])) != 1) {
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();
            return $v_result;
          }

          // ----- Look for user callback abort
          if ($v_result1 == 2) {
          	break;
          }
        }
        // ----- Look for normal extraction
        else {
          // ----- Extracting the file
          $v_result1 = $this->privExtractFile($v_header,
		                                      $p_path, $p_remove_path,
											  $p_remove_all_path,
											  $p_options);
          if ($v_result1 < 1) {
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();
            return $v_result1;
          }
          
		  $v_nb_extracted++;
		  
		  //echo memory_get_usage().' - '.$v_nb_extracted.' - '.$v_header['filename']."\n";
          
		  // ----- Get the only interesting attributes
          /*if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted++])) != 1)
          {
            // ----- Close the zip file
            $this->privCloseFd();
            $this->privSwapBackMagicQuotes();

            return $v_result;
          }*/

          // ----- Look for user callback abort
          if ($v_result1 == 2) {
          	break;
          }
        }
      }
    }

    // ----- Close the zip file
    $this->privCloseFd();
    $this->privSwapBackMagicQuotes();

    // ----- Return
    return $v_result;
  }
  
  function _privReadEndCentralDir(&$p_central_dir)
  {
    $v_result=1;

    // ----- Go to the end of the zip file
    $v_size = filesize($this->zipname);
    @fseek($this->zip_fd, $v_size);
    if (@ftell($this->zip_fd) != $v_size)
    {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to go to the end of the archive \''.$this->zipname.'\'');

      // ----- Return
      return PclZip::errorCode();
    }

    // ----- First try : look if this is an archive with no commentaries (most of the time)
    // in this case the end of central dir is at 22 bytes of the file end
    $v_found = 0;
    if ($v_size > 26) {
      @fseek($this->zip_fd, $v_size-22);
      if (($v_pos = @ftell($this->zip_fd)) != ($v_size-22))
      {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');

        // ----- Return
        return PclZip::errorCode();
      }

      // ----- Read for bytes
      $v_binary_data = @fread($this->zip_fd, 4);
      $v_data = @unpack('Vid', $v_binary_data);

      // ----- Check signature
      if ($v_data['id'] == 0x06054b50) {
        $v_found = 1;
      }

      $v_pos = ftell($this->zip_fd);
    }

    // ----- Go back to the maximum possible size of the Central Dir End Record
    if (!$v_found) {
      $v_maximum_size = 65557; // 0xFFFF + 22;
      if ($v_maximum_size > $v_size)
        $v_maximum_size = $v_size;
      @fseek($this->zip_fd, $v_size-$v_maximum_size);
      if (@ftell($this->zip_fd) != ($v_size-$v_maximum_size))
      {
        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');

        // ----- Return
        return PclZip::errorCode();
      }

      // ----- Read byte per byte in order to find the signature
      $v_pos = ftell($this->zip_fd);
      $v_bytes = 0x00000000;
      while ($v_pos < $v_size)
      {
        // ----- Read a byte
        $v_byte = @fread($this->zip_fd, 1);

        // -----  Add the byte
        //$v_bytes = ($v_bytes << 8) | Ord($v_byte);
        // Note we mask the old value down such that once shifted we can never end up with more than a 32bit number 
        // Otherwise on systems where we have 64bit integers the check below for the magic number will fail. 
        $v_bytes = ( ($v_bytes & 0xFFFFFF) << 8) | Ord($v_byte); 

        // ----- Compare the bytes
        if ($v_bytes == 0x504b0506)
        {
          $v_pos++;
          break;
        }

        $v_pos++;
      }

      // ----- Look if not found end of central dir
      if ($v_pos == $v_size)
      {

        // ----- Error log
        PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Unable to find End of Central Dir Record signature");

        // ----- Return
        return PclZip::errorCode();
      }
    }

    // ----- Read the first 18 bytes of the header
    $v_binary_data = fread($this->zip_fd, 18);

    // ----- Look for invalid block size
    if (strlen($v_binary_data) != 18)
    {

      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid End of Central Dir Record size : ".strlen($v_binary_data));

      // ----- Return
      return PclZip::errorCode();
    }

    // ----- Extract the values
    $v_data = unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', $v_binary_data);

    // ----- Check the global size
    if (($v_pos + $v_data['comment_size'] + 18) != $v_size) {

	  // ----- Removed in release 2.2 see readme file
	  // The check of the file size is a little too strict.
	  // Some bugs where found when a zip is encrypted/decrypted with 'crypt'.
	  // While decrypted, zip has training 0 bytes
	  if (0) {
      // ----- Error log
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT,
	                       'The central dir is not at the end of the archive.'
						   .' Some trailing bytes exists after the archive.');

      // ----- Return
      return PclZip::errorCode();
	  }
    }

    // ----- Get comment
    if ($v_data['comment_size'] != 0) {
      $p_central_dir['comment'] = fread($this->zip_fd, $v_data['comment_size']);
    }
    else
      $p_central_dir['comment'] = '';

    $p_central_dir['entries'] = $v_data['entries'];
    $p_central_dir['disk_entries'] = $v_data['disk_entries'];
    $p_central_dir['offset'] = $v_data['offset'];
    $p_central_dir['size'] = $v_data['size'];
    $p_central_dir['disk'] = $v_data['disk'];
    $p_central_dir['disk_start'] = $v_data['disk_start'];
	
	//r_print($p_central_dir);
	
	 // ----- Read next Central dir entry
    @rewind($this->zip_fd);
    if (@fseek($this->zip_fd, $p_central_dir['offset'])){
		return $v_result; // Return the original result
	}
	
	//$start = microtime_float();
	
	for($i=1; $i<=200000; $i++){
		$binary_data = @fread($this->zip_fd, 4);
		$_data = unpack('Vid', $binary_data);
		if ($_data['id'] != 0x02014b50){
			if($_data['id'] == 0x06054b50){
				$total = $i - 1;
			}
			break;
		}
		
		fread($this->zip_fd, 24);
		
		$binary_data = fread($this->zip_fd, 6);
		$_header = unpack('vfilename_len/vextra_len/vcomment_len', $binary_data);
		
		fread($this->zip_fd, 12);
		
		// ----- Get filename
		if ($_header['filename_len'] != 0)
		fread($this->zip_fd, $_header['filename_len']);
		
		// ----- Get extra
		if ($_header['extra_len'] != 0)
		fread($this->zip_fd, $_header['extra_len']);
		
		// ----- Get comment
		if ($_header['comment_len'] != 0)
		fread($this->zip_fd, $_header['comment_len']);
		
		unset($_header);
		
	}// End of for loop
	
	if(!empty($total)){
		$p_central_dir['entries'] = $total;
    	$p_central_dir['disk_entries'] = $total;
	}
	
	//echo (microtime_float() - $start)."\n";
	
	//r_print($p_central_dir);
	
    // TBC
    //for(reset($p_central_dir); $key = key($p_central_dir); next($p_central_dir)) {
    //}

    // ----- Return
    return $v_result;
  }
  
  function _add($p_filelist)
  {
    $v_result=1;

    // ----- Reset the error handler
    $this->privErrorReset();

    // ----- Set default values
    $v_options = array();
    $v_options[PCLZIP_OPT_NO_COMPRESSION] = FALSE;

    // ----- Look for variable options arguments
    $v_size = func_num_args();

    // ----- Look for arguments
    if ($v_size > 1) {
      // ----- Get the arguments
      $v_arg_list = func_get_args();

      // ----- Remove form the options list the first argument
      array_shift($v_arg_list);
      $v_size--;

      // ----- Look for first arg
      if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {

        // ----- Parse the options
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                            array (PCLZIP_OPT_REMOVE_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
                                                   PCLZIP_OPT_ADD_PATH => 'optional',
                                                   PCLZIP_CB_PRE_ADD => 'optional',
                                                   PCLZIP_CB_POST_ADD => 'optional',
                                                   PCLZIP_OPT_NO_COMPRESSION => 'optional',
                                                   PCLZIP_OPT_COMMENT => 'optional',
                                                   PCLZIP_OPT_ADD_COMMENT => 'optional',
                                                   PCLZIP_OPT_PREPEND_COMMENT => 'optional',
                                                   PCLZIP_OPT_TEMP_FILE_THRESHOLD => 'optional',
                                                   PCLZIP_OPT_TEMP_FILE_ON => 'optional',
                                                   PCLZIP_OPT_TEMP_FILE_OFF => 'optional'
                                                   //, PCLZIP_OPT_CRYPT => 'optional'
												   ));
        if ($v_result != 1) {
          return 0;
        }
      }

      // ----- Look for 2 args
      // Here we need to support the first historic synopsis of the
      // method.
      else {

        // ----- Get the first argument
        $v_options[PCLZIP_OPT_ADD_PATH] = $v_add_path = $v_arg_list[0];

        // ----- Look for the optional second argument
        if ($v_size == 2) {
          $v_options[PCLZIP_OPT_REMOVE_PATH] = $v_arg_list[1];
        }
        else if ($v_size > 2) {
          // ----- Error log
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");

          // ----- Return
          return 0;
        }
      }
    }

    // ----- Look for default option values
    $this->privOptionDefaultThreshold($v_options);

    // ----- Init
    $v_string_list = array();
    $v_att_list = array();
    $v_filedescr_list = array();
    $p_result_list = array();
    
    // ----- Look if the $p_filelist is really an array
    if (is_array($p_filelist)) {
    
      // ----- Look if the first element is also an array
      //       This will mean that this is a file description entry
      if (isset($p_filelist[0]) && is_array($p_filelist[0])) {
        $v_att_list = $p_filelist;
      }
      
      // ----- The list is a list of string names
      else {
        $v_string_list = $p_filelist;
      }
    }

    // ----- Look if the $p_filelist is a string
    else if (is_string($p_filelist)) {
      // ----- Create a list from the string
      $v_string_list = explode(PCLZIP_SEPARATOR, $p_filelist);
    }

    // ----- Invalid variable type for $p_filelist
    else {
      PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid variable type '".gettype($p_filelist)."' for p_filelist");
      return 0;
    }
    
    // ----- Reformat the string list
    if (sizeof($v_string_list) != 0) {
      foreach ($v_string_list as $v_string) {
        $v_att_list[][PCLZIP_ATT_FILE_NAME] = $v_string;
      }
    }
    
    // ----- For each file in the list check the attributes
    $v_supported_attributes
    = array ( PCLZIP_ATT_FILE_NAME => 'mandatory'
             ,PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'optional'
             ,PCLZIP_ATT_FILE_NEW_FULL_NAME => 'optional'
             ,PCLZIP_ATT_FILE_MTIME => 'optional'
             ,PCLZIP_ATT_FILE_CONTENT => 'optional'
             ,PCLZIP_ATT_FILE_COMMENT => 'optional'
						);
    foreach ($v_att_list as $v_entry) {
      $v_result = $this->privFileDescrParseAtt($v_entry,
                                               $v_filedescr_list[],
                                               $v_options,
                                               $v_supported_attributes);
      if ($v_result != 1) {
        return 0;
      }
    }

    /*// ----- Expand the filelist (expand directories)
    $v_result = $this->privFileDescrExpand($v_filedescr_list, $v_options);
    if ($v_result != 1) {
      return 0;
    }*/

    // ----- Call the create fct
    $v_result = $this->_privAdd($v_filedescr_list, $p_result_list, $v_options);
    if ($v_result != 1) {
      return 0;
    }

    // ----- Return
    return $p_result_list;
  }
  
  function _privAdd($p_filedescr_list, &$p_result_list, &$p_options)
  {
    $v_result=1;
    $v_list_detail = array();

    // ----- Look if the archive exists or is empty
    if ((!is_file($this->zipname)) || (filesize($this->zipname) == 0))
    {
	
	  $v_result = $this->privFileDescrExpand($p_filedescr_list, $p_options);
      if ($v_result != 1) {
        return 0;
      }
	  
      // ----- Do a create
      $v_result = $this->privCreate($p_filedescr_list, $p_result_list, $p_options);
	  
      // ----- Return
      return $v_result;
    }
    // ----- Magic quotes trick
    $this->privDisableMagicQuotes();

    // ----- Open the zip file
    if (($v_result=$this->privOpenFd('rb')) != 1)
    {
      // ----- Magic quotes trick
      $this->privSwapBackMagicQuotes();

      // ----- Return
      return $v_result;
    }

    // ----- Read the central directory informations
    $v_central_dir = array();
    if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
    {
      $this->privCloseFd();
      $this->privSwapBackMagicQuotes();
      return $v_result;
    }

    // ----- Go to beginning of File
    @rewind($this->zip_fd);

    // ----- Creates a temporay file
    $v_zip_temp_name = PCLZIP_TEMPORARY_DIR.uniqid('pclzip-').'.tmp';

    // ----- Open the temporary file in write mode
    if (($v_zip_temp_fd = @fopen($v_zip_temp_name, 'wb')) == 0)
    {
      $this->privCloseFd();
      $this->privSwapBackMagicQuotes();

      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open temporary file \''.$v_zip_temp_name.'\' in binary write mode');

      // ----- Return
      return PclZip::errorCode();
    }

    // ----- Copy the files from the archive to the temporary file
    // TBC : Here I should better append the file and go back to erase the central dir
    $v_size = $v_central_dir['offset'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      $v_buffer = fread($this->zip_fd, $v_read_size);
      @fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }

    // ----- Swap the file descriptor
    // Here is a trick : I swap the temporary fd with the zip fd, in order to use
    // the following methods on the temporary fil and not the real archive
    $v_swap = $this->zip_fd;
    $this->zip_fd = $v_zip_temp_fd;
    $v_zip_temp_fd = $v_swap;
	
	global $tempheader;
	
	$tempheader = '';
	
	$tempheaderfile = PCLZIP_TEMPORARY_DIR.uniqid('softaculous').'.tmp';
	if(($tempheader = @fopen($tempheaderfile, 'wb')) == 0){
		fclose($v_zip_temp_fd);
		$this->privCloseFd();
		@unlink($v_zip_temp_name);
		$this->privSwapBackMagicQuotes();
		PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open temporary file \''.$tempheaderfile.'\' in binary write mode');
		return 88888;
	}
	
	//echo 'privAddList - '.memory_get_usage()."\n"; 
	
    // ----- Add the files
    if (($v_result = $this->_privAddFileList($p_filedescr_list, $p_result_list, $p_options)) != 1)
    {
      fclose($v_zip_temp_fd);
      $this->privCloseFd();
      @unlink($v_zip_temp_name);
      $this->privSwapBackMagicQuotes();

      // ----- Return
      return $v_result;
    }
    
	//echo 'privAddList END - '.memory_get_usage()."\n"; 

    // ----- Store the offset of the central dir
    $v_offset = @ftell($this->zip_fd);

    //echo '1 - '.memory_get_usage()."\n"; 
    // ----- Copy the block of file headers from the old archive
    $v_size = $v_central_dir['size'];
    while ($v_size != 0)
    {
      $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
      $v_buffer = @fread($v_zip_temp_fd, $v_read_size);
      @fwrite($this->zip_fd, $v_buffer, $v_read_size);
      $v_size -= $v_read_size;
    }
	
    //echo '2 - '.memory_get_usage()."\n"; 
    // ----- Create the Central Dir files header
    $v_count = $p_result_list;
	
	// Append the Temporary file we created.
	@fclose($tempheader);
	
	if(($tempfd = @fopen($tempheaderfile, "rb")) == 0){
		fclose($v_zip_temp_fd);
		$this->privCloseFd();
		@unlink($v_zip_temp_name);
		$this->privSwapBackMagicQuotes();
		return 88888;
	}
	
	$t_size = filesize($tempheaderfile);
	while ($t_size != 0)
    {
      $t_read_size = ($t_size < PCLZIP_READ_BLOCK_SIZE ? $t_size : PCLZIP_READ_BLOCK_SIZE);
      $t_buffer = @fread($tempfd, $t_read_size);
      @fwrite($this->zip_fd, $t_buffer, $t_read_size);
      $t_size -= $t_read_size;
    }
	
	@fclose($tempfd);
	@unlink($tempheaderfile);
	
    //echo '3 - '.memory_get_usage()."\n"; 
    // ----- Zip file comment
    $v_comment = $v_central_dir['comment'];
    if (isset($p_options[PCLZIP_OPT_COMMENT])) {
      $v_comment = $p_options[PCLZIP_OPT_COMMENT];
    }
    if (isset($p_options[PCLZIP_OPT_ADD_COMMENT])) {
      $v_comment = $v_comment.$p_options[PCLZIP_OPT_ADD_COMMENT];
    }
    if (isset($p_options[PCLZIP_OPT_PREPEND_COMMENT])) {
      $v_comment = $p_options[PCLZIP_OPT_PREPEND_COMMENT].$v_comment;
    }
    //echo '4 - '.memory_get_usage()."\n"; 
    // ----- Calculate the size of the central header
    $v_size = @ftell($this->zip_fd)-$v_offset;

    // ----- Create the central dir footer
    if (($v_result = $this->privWriteCentralHeader($v_count+$v_central_dir['entries'], $v_size, $v_offset, $v_comment)) != 1)
    {
      // ----- Reset the file list
      
      $this->privSwapBackMagicQuotes();

      // ----- Return
      return $v_result;
    }
    //echo '5 - '.memory_get_usage()."\n"; 
    // ----- Swap back the file descriptor
    $v_swap = $this->zip_fd;
    $this->zip_fd = $v_zip_temp_fd;
    $v_zip_temp_fd = $v_swap;
    //echo '6 - '.memory_get_usage()."\n"; 
    // ----- Close
    $this->privCloseFd();
    //echo '7 - '.memory_get_usage()."\n"; 
    // ----- Close the temporary file
    @fclose($v_zip_temp_fd);

    // ----- Magic quotes trick
    $this->privSwapBackMagicQuotes();
    //echo '8 - '.memory_get_usage()."\n";
    // ----- Delete the zip file
    // TBC : I should test the result ...
    @unlink($this->zipname);

    // ----- Rename the temporary file
    // TBC : I should test the result ...
    //@rename($v_zip_temp_name, $this->zipname);
    PclZipUtilRename($v_zip_temp_name, $this->zipname);
    //echo '9 - '.memory_get_usage()."\n";
    // ----- Return
    return $v_result;
  }
  
  function _privAddFileList($p_filedescr_list, &$p_result_list, &$p_options)
  {
    $v_result=1;
	
	global $_soft_result_list, $_soft_nb;
	
    $_soft_result_list = array();
	$_soft_nb = 0;	
	
	$v_result = $this->_privFileDescrExpand($p_filedescr_list, $p_options);
    if ($v_result != 1) {
      return 0;
    }
	
	$p_result_list = $_soft_nb;
	
    // ----- Return
    return $v_result;
  }

  function _privFileDescrExpand(&$p_filedescr_list, &$p_options)
  {
  	global $error, $l;
    $v_result=1;
 
    // ----- Create a result list
    $v_result_list = array();
    // r_print($p_filedescr_list);
    
    // ----- Look each entry
    for ($i=0; $i<sizeof($p_filedescr_list); $i++) {
      // ----- Get filedescr
      $v_descr = $p_filedescr_list[$i];
      
      // ----- Reduce the filename
      $v_descr['filename'] = PclZipUtilTranslateWinPath($v_descr['filename'], false);
      $v_descr['filename'] = PclZipUtilPathReduction($v_descr['filename']);
      
      // ----- Look for real file or folder
      if (file_exists($v_descr['filename'])) {
        if (@is_file($v_descr['filename'])) {
          $v_descr['type'] = 'file';
        }
        else if (@is_dir($v_descr['filename'])) {
          $v_descr['type'] = 'folder';
        }
        else if (@is_link($v_descr['filename'])) {
          // skip
          continue;
        }
        else {
          // skip
          continue;
        }
      }
      
      // ----- Look for string added as file
      else if (isset($v_descr['content'])) {
        $v_descr['type'] = 'virtual_file';
      }
      
      // ----- Missing file
      else {
		$error['read'] = lang_vars($l['could_not_read'], array($v_descr['filename']));
		// ----- Error log
		PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "File '".$v_descr['filename']."' does not exist");
        // ----- Return
		return PclZip::errorCode();
      }
      
      // ----- Calculate the stored filename
      $this->privCalculateStoredFilename($v_descr, $p_options);
      //r_print($v_descr);
	  
	  ///////////////////////////////////////
	  // Softaculous Change
	  // We add the files here itself
	  ///////////////////////////////////////
	  
	  global $_soft_result_list, $_soft_nb;
	  
	  $_file = $v_descr;
	  
	  // ----- Format the filename
	  $_file['filename'] = PclZipUtilTranslateWinPath($_file['filename'], false);
	  
	  // ----- Skip empty file names
      // TBC : Can this be possible ? not checked in DescrParseAtt ?
      if ($_file['filename'] == "") {
        continue;
      }

      // ----- Check the filename
      if (   ($_file['type'] != 'virtual_file')
          && (!file_exists($_file['filename']))) {
        PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "File '".$_file['filename']."' does not exist");
        return PclZip::errorCode();
      }
	  
	  // ----- Look if it is a file or a dir with no all path remove option
      // or a dir with all its path removed
//      if (   (is_file($p_filedescr_list[$j]['filename']))
//          || (   is_dir($p_filedescr_list[$j]['filename'])
      if (   ($_file['type'] == 'file')
          || ($_file['type'] == 'virtual_file')
          || (   ($_file['type'] == 'folder')
              && (   !isset($p_options[PCLZIP_OPT_REMOVE_ALL_PATH])
                  || !$p_options[PCLZIP_OPT_REMOVE_ALL_PATH]))
          ) {
		
		$v_header = array();
		
        // ----- Add the file
        $v_result = $this->privAddFile($_file, $v_header, $p_options);
        if ($v_result != 1) {
          return $v_result;
        }
		
		if ($v_header['status'] == 'ok') {
          if (($v_result = $this->_privWriteCentralFileHeader($v_header)) != 1) {
            // ----- Return
            return $v_result;
          }
		
		  $_soft_nb++;
		}
		
		unset($v_header);
		
        // ----- Store the file infos
        //$_soft_result_list[$_soft_nb++] = $v_header;
		
	  }
	  ////////////////////////////////////////
	  // End of Adding the Files
	  ////////////////////////////////////////
	  
      // ----- Add the descriptor in result list
      $v_result_list[sizeof($v_result_list)] = $v_descr;
    //r_print($v_descr);
      // ----- Look for folder
      if ($v_descr['type'] == 'folder') {
        // ----- List of items in folder
        $v_dirlist_descr = array();
        $v_dirlist_nb = 0; 
        if (($v_folder_handler = @opendir($v_descr['filename']))) {
          while (($v_item_handler = @readdir($v_folder_handler)) !== false) {

            // ----- Skip '.' and '..'
            if (($v_item_handler == '.') || ($v_item_handler == '..')) {
                continue;
            }
            
            // ----- Compose the full filename
            $v_dirlist_descr[$v_dirlist_nb]['filename'] = $v_descr['filename'].'/'.$v_item_handler;
            
            // ----- Look for different stored filename
            // Because the name of the folder was changed, the name of the
            // files/sub-folders also change
            if (($v_descr['stored_filename'] != $v_descr['filename'])
                 && (!isset($p_options[PCLZIP_OPT_REMOVE_ALL_PATH]))) {
              if ($v_descr['stored_filename'] != '') {
                $v_dirlist_descr[$v_dirlist_nb]['new_full_name'] = $v_descr['stored_filename'].'/'.$v_item_handler;
              }
              else {
                $v_dirlist_descr[$v_dirlist_nb]['new_full_name'] = $v_item_handler;
              }
            }
            $v_dirlist_nb++;
            //echo $v_dirlist_nb."\n";  
          }
          
          @closedir($v_folder_handler);
        }
        else {
          // TBC : unable to open folder in read mode
        }
		
		//r_print($v_dirlist_descr);
		
        // ----- Expand each element of the list
        if ($v_dirlist_nb != 0) {
          // ----- Expand
          if (($v_result = $this->_privFileDescrExpand($v_dirlist_descr, $p_options)) != 1) {
            return $v_result;
          }
          
          // ----- Concat the resulting list
          //$v_result_list = array_merge($v_result_list, $v_dirlist_descr);
        }
        else {
        }
          
        // ----- Free local array
        unset($v_dirlist_descr);
      }
    }
    
    // ----- Get the result list
    //$p_filedescr_list = $v_result_list;

    // ----- Return
    return $v_result;
  }
  
  function _privWriteCentralFileHeader(&$p_header)
  {
    $v_result=1;
	
	global $tempheader;

    // TBC
    //for(reset($p_header); $key = key($p_header); next($p_header)) {
    //}

    // ----- Transform UNIX mtime to DOS format mdate/mtime
    $v_date = getdate($p_header['mtime']);
    $v_mtime = ($v_date['hours']<<11) + ($v_date['minutes']<<5) + $v_date['seconds']/2;
    $v_mdate = (($v_date['year']-1980)<<9) + ($v_date['mon']<<5) + $v_date['mday'];


    // ----- Packed data
    $v_binary_data = pack("VvvvvvvVVVvvvvvVV", 0x02014b50,
	                      $p_header['version'], $p_header['version_extracted'],
                          $p_header['flag'], $p_header['compression'],
						  $v_mtime, $v_mdate, $p_header['crc'],
                          $p_header['compressed_size'], $p_header['size'],
                          strlen($p_header['stored_filename']),
						  $p_header['extra_len'], $p_header['comment_len'],
                          $p_header['disk'], $p_header['internal'],
						  $p_header['external'], $p_header['offset']);

    // ----- Write the 42 bytes of the header in the zip file
    fputs($tempheader, $v_binary_data, 46);

    // ----- Write the variable fields
    if (strlen($p_header['stored_filename']) != 0)
    {
      fputs($tempheader, $p_header['stored_filename'], strlen($p_header['stored_filename']));
    }
    if ($p_header['extra_len'] != 0)
    {
      fputs($tempheader, $p_header['extra'], $p_header['extra_len']);
    }
    if ($p_header['comment_len'] != 0)
    {
      fputs($tempheader, $p_header['comment'], $p_header['comment_len']);
    }

    // ----- Return
    return $v_result;
  }

}

