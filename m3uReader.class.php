<?php
/**
* Reads m3u files, extracts Title, Seconds and Directory.
* I consider this class useless, i imagine some cases in which
* this class can be used, but i just made that for learn new things.
* I did my best and want to improve.
* 
* @author Eduardo Lopes <EduardoLopesDS@gmail.com>
* @version 0.2 
* @copyright Copyright (c) 2012, Eduardo Lopes
* @license New BSD License
* @todo create a method that return data in JSON format. 
*
*/

class m3uReader
{
    /**
    * Return all content of file, without the first line ("#EXTM3U").
    * @return Array
    */
    public $file;
	
    /**
    * Return the '#ExtM3U:' lines without treatment.
    * @return Array
    */
    protected $rawExtM3U;

    /**
    * Return the Dir lines.
    * @return Array
    */
    public $dir;

    /**
    * Return seconds of tracks.
    * @return Array
    */
    public $seconds;

    /**
    * Return minutes of tracks.
    * @return Array
    */
    public $minutes;

    /**
    * Return title of tracks (format Artist - Name track | Name track) .
    * @return Array
    */
    public $title;

    /**
    * Validate and init methods
    * @return void	
    * @throws Exception if file does not exist or if the m3u file is no valid.
    */
    public function __construct($file)
    {
        if(file_exists($file))
            $this->file = file($file);
        else
            throw new Exception("File does not exist!", 1);

        if(strtoupper(trim($this->file[0])) != '#EXTM3U')
            throw new Exception("This file is not a valid M3U!", 1);

        array_shift($this->file);

        //i guess this is not a good way, but work. I'm looking for a better way.
        self::filterExtM3U();
    }

    /**
    * Return the '#ExtM3U:' lines without tratmant.
    * @return Array
    */
    public function getExtM3U()
    {
        for ($i=0; $i < count($this->file); $i++) { 
            if(($i % 2) == 0)
                $this->rawExtM3U[] = $this->file[$i];             
        }
        
        return $this->rawExtM3U; 
    }

    /**
    * Return the Dir lines.
    * @return Array
    */
    public function getDir()
    {
        for ($i=0; $i < count($this->file); $i++) { 
            if(($i % 2) == 1)
                $this->dir[] = $this->file[$i];
        }
        
        return $this->dir;
    }

    /**
    * Filter '#ExtM3U:' lines, define  $this->seconds (array) and $this->title (array);
    * @return void
    */
    public function filterExtM3U()
    {
        foreach(self::getExtM3U() as $ExtM3U) {	      
            $ExtM3U = substr_replace($ExtM3U, "", 0, 8);
            $ExtM3U = explode(",", $ExtM3U, 2);
            $this->seconds[]   = $ExtM3U[0];
            $this->title[]    = $ExtM3U[1];	  	  
        }
    }

    /**
    * Return titles of track.
    * @return Array
    */
    public function getTitle()
    {
        return $this->title;
    }

    /**
    * Return seconds of track.
    * @return Array
    */
    public function getSeconds()
    {
        return $this->seconds;
    }

    /**
    * Return minutes of track.
    * @return Array
    */
    public function getMinutes()
    {
        return self::secondsInMinutes();
    }

    /**
    * Convert seconds of tracks in minutes
    * @return Array
    */
    public function secondsInMinutes()
    {
        for ($i=0; $i < count($this->seconds); $i++) { 
            if(gmdate('H', $this->seconds[$i]) <= 0)
                $this->minutes[$i] = gmdate('i:s', $this->seconds[$i]);
            else
                $this->minutes[$i] = gmdate('H:i:s', $this->seconds[$i]);
        }
        
        return $this->minutes;
    }

}

/**
* Copyright (c) 2012, Eduardo Lopes
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*
*     * Redistributions of source code must retain the above copyright
*       notice, this list of conditions and the following disclaimer.
*
*     * Redistributions in binary form must reproduce the above copyright
*       notice, this list of conditions and the following disclaimer in the
*       documentation and/or other materials provided with the distribution.
*
*     * Neither the name of the Eduardo Lopes nor the names of its contributors
*		may be used to endorse or promote products derived from this software
*       without specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
* ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
* DISCLAIMED. IN NO EVENT SHALL COPYRIGHT OWNER BE LIABLE FOR ANY
* DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
* (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
* LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
* ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
* (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
* SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/