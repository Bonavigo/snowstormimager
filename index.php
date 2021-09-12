<?php
	error_reporting(E_ALL);
	header("Access-Control-Allow-Origin: *");
	function curl($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) ecko/20080311 Firefox/2.0.0.13');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($httpCode >= 200 AND $httpCode < 300) {
			return $result;
		} else {
			return false;
		}
	}

	class Snowstorm
	{
		public $path;
		public $team;
		public $head;
		public $head_image;
		public $width = 33;
		public $height = 56;
		public $head_width = 27;
		public $head_height = 30;
		public $frame;
		public $hotel;
		public $head_direction_hb = 2;
		public $head_pos_x = 2;
		public $head_pos_y = 6;
		public $body_pos_x = 2;
		public $body_pos_y = 18;

		function __construct($team, $head, $frame, $hotel)
		{
			$this->team = $team;
			$this->head = $head;
			$this->frame = (int) $frame;
			$this->hotel = $hotel;
			switch ($this->frame) {
				case 1:
					if ($this->team == 'blue') {
						$this->path = 'frames/blue/12_jacket_U_snowwar4_team1_sh_std_ch_20000_2_0.png';
					} else {
						$this->path = 'frames/red/7_jacket_U_snowwar4_team2_sh_std_ch_20001_2_0.png';
					}
					break;
				case 2:
					if ($this->team == 'blue') {
						$this->path = 'frames/blue/24_jacket_U_snowwar4_team1_sh_swrun_ch_20000_2_1.png';
					} else {
						$this->path = 'frames/red/24_jacket_U_snowwar4_team2_sh_swrun_ch_20001_2_3.png';
					}
					break;
				case 3:
					if ($this->team == 'blue') {
						$this->path = 'frames/blue/26_jacket_U_snowwar4_team1_sh_swrun_ch_20000_2_0.png';
					} else {
						$this->path = 'frames/red/23_jacket_U_snowwar4_team2_sh_swrun_ch_20001_2_2.png';
					}
					break;
				case 4:
					if ($this->team == 'blue') {
						$this->path = 'frames/blue/16_jacket_U_snowwar4_team1_sh_std_ch_20000_3_0.png';
					} else {
						$this->path = 'frames/red/17_jacket_U_snowwar4_team2_sh_std_ch_20001_3_0.png';
					}
					$this->head_direction_hb = 3;
					$this->head_pos_x = 3;
					$this->head_pos_y = 6;
					$this->body_pos_x = 6;
					$this->body_pos_y = 22;
					break;
				default:
					if ($this->team == 'blue') {
						$this->path = 'frames/blue/12_jacket_U_snowwar4_team1_sh_std_ch_20000_2_0.png';
					} else {
						$this->path = 'frames/red/7_jacket_U_snowwar4_team2_sh_std_ch_20001_2_0.png';
					}
					break;
			}
			switch ($head) {
				case 'new':
					$head_1 = curl("https://www.habbo{$this->hotel}/habbo-imaging/avatarimage?img_format=png&user={$_GET['user']}&direction=2&head_direction={$this->head_direction_hb}&size=m&headonly=1");
					if ($head_1) {
						$head_tmp = tempnam("/tmp", "head");
						file_put_contents($head_tmp, $head_1);
						$head_processed = new Imagick($head_tmp);
						$head_processed->resizeImage($this->head_width, $this->head_height, imagick::FILTER_LANCZOS, 0.9, true);
						$head_processed->cropImage($this->head_width, $this->head_height, 0, 0);
						$this->head_image = $head_processed;
					} else {
						$this->not_found();
					}
					break;
				
				default:
					$head_1 = curl("https://www.habbo{$this->hotel}/habbo-imaging/avatarimage?img_format=png&user={$_GET['user']}&direction=2&head_direction={$this->head_direction_hb}&size=s&headonly=1");
					if ($head_1) {
						$head_tmp = tempnam("/tmp", "head");
						file_put_contents($head_tmp, $head_1);
						$this->head_image = new Imagick($head_tmp);
					} else {
						$this->not_found();
					}
					break;
			}
		}
		public function generate()
		{
			header('Content-type: image/png');
			header('Content-Disposition: inline; filename="avatarimage_snow.png"');
			$background = new Imagick();
			$background->newImage($this->width, $this->height, new ImagickPixel('transparent'));
			$background->setImageFormat('png');
			$body = new Imagick($this->path);
			$background->compositeImage($body, Imagick::COMPOSITE_DEFAULT, $this->body_pos_x, $this->body_pos_y);
			$background->compositeImage($this->head_image, Imagick::COMPOSITE_DEFAULT, $this->head_pos_x, $this->head_pos_y);
			return $background;
		}
		public function generate_gif()
		{
			header('Content-type: image/gif');
			header('Content-Disposition: inline; filename="avatarimage_snow.gif"');

			switch ($this->team) {
				case 'blue':
					$frames = array(
						"frames/blue/4_jacket_U_snowwar4_team1_sh_swrun_ch_20000_2_3.png",
						"frames/blue/26_jacket_U_snowwar4_team1_sh_swrun_ch_20000_2_0.png",
						"frames/blue/24_jacket_U_snowwar4_team1_sh_swrun_ch_20000_2_1.png",
						"frames/blue/5_jacket_U_snowwar4_team1_sh_swrun_ch_20000_2_2.png"
					);
					break;
				
				default:
					$frames = array(
						"frames/red/24_jacket_U_snowwar4_team2_sh_swrun_ch_20001_2_3.png",
						"frames/red/23_jacket_U_snowwar4_team2_sh_swrun_ch_20001_2_2.png",
						"frames/red/21_jacket_U_snowwar4_team2_sh_swrun_ch_20001_2_1.png",
						"frames/red/19_jacket_U_snowwar4_team2_sh_swrun_ch_20001_2_0.png"
					);
					break;
			}

			$GIF = new Imagick();
			$GIF->newImage($this->width, $this->height, new ImagickPixel('transparent'));
			$GIF->setImageFormat('gif');
			$GIF->removeImage();

			foreach ($frames as $frame_image) {
				$frame = new Imagick();
				$frame->newImage($this->width, $this->height, new ImagickPixel("rgb(255, 0, 255)"));
				$frame->setImageFormat('gif');
				$body = new Imagick($frame_image);
				$frame->compositeImage($body, Imagick::COMPOSITE_DEFAULT, $this->body_pos_x, $this->body_pos_y);
				$frame->compositeImage($this->head_image, Imagick::COMPOSITE_DEFAULT, $this->head_pos_x, $this->head_pos_y);
				$frame->setImageDelay(12);
				$GIF->addImage($frame);
			}

			return $GIF->getImagesBlob();
		}
		public function not_found() {
			header("HTTP/1.0 404 Not Found");
			exit();
		}
	}

	if (!$_GET['team']) {
		$_GET['team'] = 'blue';
	}
	if (!$_GET['head']) {
		$_GET['head'] = 'old';
	}
	if (!$_GET['frame']) {
		$_GET['frame'] = 1;
	}
	if (!$_GET['hotel']) {
		$_GET['hotel'] = '.com.br';
	}
	if (!$_GET['animation']) {
		$_GET['animation'] = '';
	}

	if (!$_GET['user']) {
		header("HTTP/1.0 404 Not Found");
		exit();
	} else {
		$snowy = new Snowstorm($_GET['team'], $_GET['head'], $_GET['frame'], $_GET['hotel']);
		if ($_GET['animation'] == 'walk') {
			echo $snowy->generate_gif();
		} else {
			echo $snowy->generate();
		}
	}
?>