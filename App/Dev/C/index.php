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

/*
Private Sub cmdLogin_Click()
Static num As Integer			// 静态变量  num 是 int型
Dim flg As Boolean				// **变量	flg 是 布尔型
If txtpassword.Text = "" Then	// 如果输入的 Text是""(空的)
MsgBox "请输入密码！", vbExclamation, "提示"		// 提示 请输入密码！
txtpassword.SetFocus			//	(这个好像是) 展示输入框之类
Else							// 如果输入的 Text不是""(空的)
rs.MoveFirst
flg = False						// 将变量 flg 赋值为 false
Do While rs.EOF = False			// while循环开始 条件:当 rs没有结束
If rs("UName") = Combol.Text Then	// 赋值 rs("UName")
If rs("UPassword") = txtpassword.Text Then	// 赋值 rs("UPassword")
flg = True						// 将变量 flg 赋值为 true
Exit Do
End If							// if 结束
End If							// if 结束
rs.MoveNext
Loop							// while循环结束
rs.MoveFirst
If flg = True Then				// 如果 flg 是 true
frmLogin.Hide					// 隐藏登入框
frmSystem.Show					// 显示内容
Else							// 如果 flg 是 false
num = num + 1					// num+1
MsgBox "密码错误！", vbExclamation, "提示"	// 提示 密码错误
If num = 3 Then End				// 当 num=3 时 结束 (最多错3次)
End If
End If
End Sub
 */