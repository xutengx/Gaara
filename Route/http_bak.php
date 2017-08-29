<?php

return [
    // 数据库测试
    Route::get('/mysql','App\mysql\Contr\indexContr@indexDo'),
    // 新共能开发
    Route::get('/new','App\development\Contr\indexContr@indexDo'),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
   
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
    
    Route::group(['prefix'=>'/test/{qww}','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index\Contr' ], function(){
        Route::group(['prefix'=>'/test2','as'=> 'test2','namespace'=> 'App\test\tes'], function(){
            Route::any('/hello1',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            Route::post('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            Route::get('/hello/{ww?}',['as' => 'hello','domain'=> '192.168.43.1281', 'uses' => function (){
                return 'hello 多层路由分组嵌套';
            }]);
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            
            Route::post('/hello/{rr}',function (){
                return 'hello2 多层路由分组嵌套';
            });
            Route::post('/hello3', 'IndexContr@indexDo');

        });
        Route::any('/hello/{www}',['as' =>'www', 'middleware'=>['test'], 'domain'=> '{p}.168.43.128','uses' => function ($r, $a, $b= null, $c = null){
            

            return 'hello';
        }]);
        Route::any('/byebye',['as' => 'tt2', 'uses' => function (){
            return 'byebye';
        }]);
    }),
    Route::any('/hello',['as' => 'tt1', 'uses' => function (){
        return 'hello12312';
    }]),
            
    Route::any('/route1',['as' => 'tt1', 'uses' => 'App\development\Contr\indexContr@indexDo']),   
    // 支持隐式路由(兼容式) 
    '/'.IN_SYS => function(){
        \Main\Core\RouteImplicit::Start();
    },
    // 支持隐式路由
    Route::any('/{app}/{contr}/{action}', function ($app, $contr, $action) {
        return obj('\App/'.$app.'/Contr/'.$contr.'Contr')->$action(obj('Request'));
    })
];