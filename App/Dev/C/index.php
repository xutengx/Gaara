<?php

namespace App\Dev\C {

	class index {

		public function indexDo($num1, $num2) {
//			var_dump(is_file(ROOT.'App/Dev/C/source/add.c'));exit;
//			echo shell_exec('whoami');exit;
			$t = '/usr/bin/gcc '.ROOT.'App/Dev/C/source/add.c -o '.ROOT.'App/Dev/C/source/add';
//			var_dump($t);exit;
			$a = [];
			$b = [];
			echo passthru($t);
			var_dump($a);
			var_dump($b);
			exit;
//			$command = './test ' . $_POST['a'] . ' ' . $_POST['b'];
			$t2 = ROOT."App/Dev/C/source/add $num1 $num2";
			$result	 = passthru($t2);
			print_r($result);
		}

	}

}
