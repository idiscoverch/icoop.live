<?php
	
	session_start();
	
	define('AVATAR_PATH', 'img/avatar/');
	define('AVATAR_SIZE', 128);
	define('AVATAR_JPEG_QUALITY', 85);
	
	
	if(isset($_FILES['image']))
    {
        if (!is_dir(AVATAR_PATH) OR !is_writable(AVATAR_PATH)) {
            echo 'Avatar folder does not exist or is not writable. Please change this via chmod 775 or 777.';
        } else {
			if (!isset($_FILES['image']) OR empty ($_FILES['image']['tmp_name'])) {
				echo 'Something went wrong with the image upload.';
			} else {
				$image_proportions = getimagesize($_FILES['image']['tmp_name']);
				
				if ($_FILES['image']['size'] > 5000000 ) {
					echo 'Avatar source file is too big. 5 Megabyte is the maximum.';
				} else {
					if ($image_proportions[0] < AVATAR_SIZE OR $image_proportions[1] < AVATAR_SIZE) {
						echo "Avatar source file's width/height is too small. Needs to be 100x100 pixel minimum.";
					} else {
						if ($image_proportions['mime'] == 'image/jpeg' || $image_proportions['mime'] == 'image/png') {
						
							$target_file_path = AVATAR_PATH . $_SESSION['id_contact'] . ".jpg";
							
							if (file_exists($target_file_path)) {
								unlink($target_file_path);
							}
							
							resizeAvatarImage($_FILES['image']['tmp_name'], $target_file_path, AVATAR_SIZE, AVATAR_SIZE, AVATAR_JPEG_QUALITY, true);
							echo '1##'.$_SESSION['id_contact'];
							
							// header( "refresh:5;url=index.php" );
							
						} else {
							echo 'Only JPEG and PNG files are supported.';
						}
					}
				}
			}
		} 
    }
	
	
	function resizeAvatarImage(
        $source_image, $destination_filename, $width = 128, $height = 128, $quality = 85, $crop = true)
    {
        $image_data = getimagesize($source_image);
        if (!$image_data) {
            return false;
        }

        // set to-be-used function according to filetype
        switch ($image_data['mime']) {
            case 'image/gif':
                $get_func = 'imagecreatefromgif';
                $suffix = ".gif";
            break;
            case 'image/jpeg';
                $get_func = 'imagecreatefromjpeg';
                $suffix = ".jpg";
            break;
            case 'image/png':
                $get_func = 'imagecreatefrompng';
                $suffix = ".png";
            break;
        }

        $img_original = call_user_func($get_func, $source_image );
        $old_width = $image_data[0];
        $old_height = $image_data[1];
        $new_width = $width;
        $new_height = $height;
        $src_x = 0;
        $src_y = 0;
        $current_ratio = round($old_width / $old_height, 2);
        $desired_ratio_after = round($width / $height, 2);
        $desired_ratio_before = round($height / $width, 2);

        if ($old_width < $width OR $old_height < $height) {
             // the desired image size is bigger than the original image. Best not to do anything at all really.
            return false;
        }

        // if crop is on: it will take an image and best fit it so it will always come out the exact specified size.
        if ($crop) {
            // create empty image of the specified size
            $new_image = imagecreatetruecolor($width, $height);

            // landscape image
            if ($current_ratio > $desired_ratio_after) {
                $new_width = $old_width * $height / $old_height;
            }

            // nearly square ratio image
            if ($current_ratio > $desired_ratio_before AND $current_ratio < $desired_ratio_after) {

                if ($old_width > $old_height) {
                    $new_height = max($width, $height);
                    $new_width = $old_width * $new_height / $old_height;
                } else {
                    $new_height = $old_height * $width / $old_width;
                }
            }

            // portrait sized image
            if ($current_ratio < $desired_ratio_before) {
                $new_height = $old_height * $width / $old_width;
            }

            // find ratio of original image to find where to crop
            $width_ratio = $old_width / $new_width;
            $height_ratio = $old_height / $new_height;

            // calculate where to crop based on the center of the image
            $src_x = floor((($new_width - $width) / 2) * $width_ratio);
            $src_y = round((($new_height - $height) / 2) * $height_ratio);
        }
        // don't crop the image, just resize it proportionally
        else {
            if ($old_width > $old_height) {
                $ratio = max($old_width, $old_height) / max($width, $height);
            } else {
                $ratio = max($old_width, $old_height) / min($width, $height);
            }

            $new_width = $old_width / $ratio;
            $new_height = $old_height / $ratio;
            $new_image = imagecreatetruecolor($new_width, $new_height);
        }

        // create avatar thumbnail
        imagecopyresampled($new_image, $img_original, 0, 0, $src_x, $src_y, $new_width, $new_height, $old_width, $old_height);

        // save it as a .jpg file with our $destination_filename parameter
        imagejpeg($new_image, $destination_filename, $quality);

        // delete "working copy" and original file, keep the thumbnail
        imagedestroy($new_image);
        imagedestroy($img_original);

        if (file_exists($destination_filename)) {
            return true;
        }
        // default return
        return false;
    }
	
	
	