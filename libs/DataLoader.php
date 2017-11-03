<?php
namespace Libs;

class DataLoader {

	protected $path;

	public function __construct($path) {
		$this->path = $path;
	}

	/**
	 * Load data from the requested file
	 *
	 * @param string $file Filename
	 * @return array Decoded file contents
	 */
	public function load($file) {
		$path = $this->getPath($file);

		if (file_exists($path)) {
			return json_decode(file_get_contents($path), true);
		}

		return [];
	}

	/**
	 * Save data to the requested file
	 *
	 * @param string $file Filename
	 * @param array  $data Contents to save to file
	 */
	public function save($file, array $data) {
		file_put_contents(json_encode($this->getPath($file)), $data);
	}

	protected function getPath($file) {
		return $this->path . "$file.json";
	}
}
