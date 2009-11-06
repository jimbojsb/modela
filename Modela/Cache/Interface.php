<?php
interface Modela_Cache_Interface
{
	public function get();
	
	public function set();
	
	public function expire();
}