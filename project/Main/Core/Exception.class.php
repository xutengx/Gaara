<?php
namespace Main\Core;
class Exception extends \Exception{
    protected $code_map = array(
        // 自动加载
        99 => 'loader'
    );
    public function __construct($message='', $code=0,$previous =null){
        parent::__construct($message, $code, $previous);
        $this->makeMessage();
    }
    protected function makeMessage(){
        if(isset($this->code_map[$this->code])) $this->{$this->code_map[$this->code]}();
    }

    /**
     * 自动加载
     * 生成适应的 message
     */
    protected function loader(){
        $str = $this->getTraceAsString();
        $str =  explode('#',$str);
        if($str[0] == '') unset($str[0]);
        $str = array_values($str);
        $this->message .= '</br>系统检测问题所在 : '.$str[4];
        $this->message .= '</br>原异常 : '.$this->getTraceAsString();
    }
}


/*

Exception {

    protected string $message ;
    protected int $code ;
    protected string $file ;
    protected int $line ;

    public __construct ([ string $message = "" [, int $code = 0 [, Exception $previous = NULL ]]] )
    final public string getMessage ( void )
    final public Exception getPrevious ( void )
    final public int getCode ( void )
    final public string getFile ( void )
    final public int getLine ( void )
    final public array getTrace ( void )
    final public string getTraceAsString ( void )
    public string __toString ( void )
    final private void __clone ( void )
}

*/