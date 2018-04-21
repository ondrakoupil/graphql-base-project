<?php

namespace App;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger {

	protected $filePath;

	protected $fileHandle = null;

	protected $runId;

	function __construct($logName, $runId) {
		$this->filePath = __DIR__ . '/../../logs/' . $logName . '.log';
		$this->runId = $runId;
	}

	function openIfNeeded() {
		if (!$this->fileHandle) {
			$this->fileHandle = fopen(
				$this->filePath,
				'a'
			);
		}
	}

	public function log($level, $message, array $context = array()) {
		$this->openIfNeeded();
		if ($message instanceof \Exception) {
			$message = get_class($message) . " at " . $message->getFile() . " line " . $message->getLine() . "\n" . $message->getMessage() . "\n" . $message->getTraceAsString();
		}
		$write = '----- ' . $this->runId . ' - ' . date('Y-m-d H:i:s') . ' - ' . $level . ' - ' . $message;
		fwrite($this->fileHandle, $write . "\n\n");
	}

}
