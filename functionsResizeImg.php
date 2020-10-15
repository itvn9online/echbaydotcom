<?php


//
class WGR_SimpleImage {
    var $image;
    var $image_width;
    var $image_height;
    var $image_type;

    function load( $filename ) {
        $image_info = getimagesize( $filename )or die( $filename );
        $this->image_width = $image_info[ 0 ];
        $this->image_height = $image_info[ 1 ];
        $this->image_type = $image_info[ 2 ];

        //
        if ( $this->image_type == IMAGETYPE_GIF ) {
            $this->image = imagecreatefromgif( $filename );
        } elseif ( $this->image_type == IMAGETYPE_PNG ) {
            $this->image = imagecreatefrompng( $filename );
        } else {
            $this->image = imagecreatefromjpeg( $filename );
        }
    }

    function save( $filename, $image_type = '', $compression = 80, $permissions = null ) {
        if ( $image_type == '' )$image_type = $this->image_type;

        if ( $image_type == IMAGETYPE_GIF ) {
            imagegif( $this->image, $filename );
        } elseif ( $image_type == IMAGETYPE_PNG ) {
            imagepng( $this->image, $filename, 0, PNG_NO_FILTER );
        } else {
            imagejpeg( $this->image, $filename, $compression );
        }

        if ( $permissions != null ) {
            chmod( $filename, $permissions );
        }
    }

    function output( $image_type = '' ) {
        if ( $image_type == '' )$image_type = $this->image_type;

        if ( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg( $this->image );
        } elseif ( $image_type == IMAGETYPE_GIF ) {
            imagegif( $this->image );
        } elseif ( $image_type == IMAGETYPE_PNG ) {
            imagepng( $this->image );
        }
    }

    function getWidth() {
        return imagesx( $this->image );
    }

    function getHeight() {
        return imagesy( $this->image );
    }

    function resizeToHeight( $height ) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize( $width, $height );
    }

    function resizeToWidth( $width ) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize( $width, $height );
    }

    function scale( $scale ) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize( $width, $height );
    }

    function resize( $width, $height ) {
        $new_image = imagecreatetruecolor( $width, $height );
        //		echo $this->image_type; exit();

        // set transparent for png file
        if ( $this->image_type == IMAGETYPE_PNG ) {
            imagealphablending( $new_image, false );
            imagesavealpha( $new_image, true );
            $transparent = imagecolorallocatealpha( $new_image, 255, 255, 255, 127 );
            imagefilledrectangle( $new_image, 0, 0, $width, $height, $transparent );
        }

        imagecopyresampled( $new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight() );

        $this->image = $new_image;
    }
}


function WGR_resize_images( $source_file, $dst_file, $new_width, $new_height, $width = 0, $height = 0 ) {
    $image = new WGR_SimpleImage();
    $image->load( $source_file );

    if ( $width == 0 || $height == 0 ) {
        $a = getimagesize( $source_file );
        $width = $a[ 0 ];
        $height = $a[ 1 ];
    }

    if ( $new_width == $new_height ) {
        if ( $width > $height ) {
            $image->resizeToWidth( $new_width );
        } else {
            $image->resizeToHeight( $new_height );
        }
        //		$image->resize( $new_width, $new_height );
    } else if ( $new_width > $new_height ) {
        $image->resizeToWidth( $new_width );
    } else {
        $image->resizeToHeight( $new_height );
    }
    //	$image->save($dst_file, '', 100);
    $image->save( $dst_file );

    //	echo ' <strong>SimpleImage</strong>; ';
}