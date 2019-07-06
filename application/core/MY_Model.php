<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    var $_db;           // DB라이브러리 참조 객체
    var $_table = '';   // 테이블 이름
    var $_key = '';     // 기본키 컬럼 이름

    var $_is_from = FALSE;  // 테이블이름이 별도로 설정되었는지 여부

    /** 생성자 */
    public function __construct()
    {
        parent::__construct();

        // "/config/database.php"의 'default'키를 갖는 설정정보를 로드하고
        // 참조객체를 리턴한다.
        // --> 두 개 이상의 Model객체를 동시에 사용할 경우
        //     서로 독립적인 쿼리 빌더 객체를 사용함으로서 서로 충돌되지 않도록 한다.
        $this->_db = $this->load->database('default', true);

        /** 멤버변수 초기화 */
        // 이 클래스가 갖는 모든 변수의 목록을 배열로 가져온다.
        $columns = get_object_vars($this);

        // 모든 멤버변수의 배열을 스캔하여 FALSE로 초기화 
        foreach($columns as $key => $value) {
            /** 멤버변수의 상태가 다음의 조건을 충족하는 경우만 수행 */
            // 1) 멤버변수 이름의 첫 글자가 언더바가 아닌 경우.
            // 2) 멤버변수의 이름이 Primary Key와 일치하지 않는 경우.
            if ( substr($key, 0, 1) != '_' && $key != $this->_key) {
                $this->{$key} = FALSE;
            }
        }
    }

    /** 소멸자 */
    public function __destruct()
    {
        // 데이터베이스 라이브러리에 대한 객체 참조를 닫고 객체를 삭제한다.
        $this->_db->close();
        unset($this->_db);
    }

    /**
     * 이 클래스의 객체가 갖는 멤버변수의 이름과 값을 활용하여
     * SQL에 처리할 컬럼과 값을 연결하는 함수.
     * 이 클래스 내부적으로 사용한다.
     * @return void
     */
    private function __init_fields()
    {
        // 이 클래스가 갖는 모든 변수의 목록을 배열로 가져온다.
        $columns = get_object_vars($this);

        // 모든 멤버변수의 배열을 스캔 
        foreach($columns as $key => $value) {
            /** 멤버변수의 상태가 다음의 조건을 충족하는 경우만 수행 */
            // 1) 멤버변수 이름의 첫 글자가 언더바가 아닌 경우.
            // 2) 멤버변수의 이름이 Primary Key와 일치하지 않는 경우.
            // 3) 변수에 저장된 값이 FALSE가 아닌 경우
            if ( substr($key, 0, 1) != '_' && $key != $this->_key && $value !== FALSE) {
                $len = strlen($value);  // 값의 글자 수

                // 멤버변수의 값 앞뒤가 {}로 묶여 있다면 MySQL의 표현식으로 처리한다.
                if ( substr($value, 0, 1) == '{' && substr($value, $len-1, 1) == '}' ) {
                    $value = substr($value, 1, $len-2);     // 앞뒤에서 한글자씩 제거
                    $this->_db->set($key, $value, false);   // CI의 자동 이스케이프 처리 방지
                } else {
                    $this->_db->set($key, $value, true);    // 일반값인 경우 이스케이프 처리
                }
            }
        }
    }

    /**
     * 멤버변수에 저장된 값을 INSERT하고 생성된 PK값을 리턴한다.
     * @return integer
     */
    public function insert()
    {
        // 위에서 구현한 메서드를 사용하여 멤버변수를 db라이브러리에 설정
        $this->__init_fields();

        // 테이블 이름을 사용하여 insert 처리를 호출한다.
        $this->_db->insert($this->_table);

        // 기본키를 의미하는 멤버변수에 insert된 데이터의 PK값을 할당한다.
        $this->{$this->_key} = $this->_db->insert_id();

        // 기본키를 리턴한다.
        return $this->{$this->_key};
    }

    /**
     * 멤버변수에 저장된 값을 UPDATE하고 수정된 행의 수를 리턴한다.
     * @param  $key - WHERE절에서 '='연산으로 사용될 PK값.
     *                이 값을 생략하고 별도의 WHERE절을 구성할 수 있다.
     * @return integer
     */
    public function update($key=false)
    {
        // 위에서 구현한 메서드를 사용하여 멤버변수를 db라이브러리에 설정
        $this->__init_fields();

        // 파라미터가 전달되었다면 Primary Key에 대한 WHERE절로 설정
        if ($key !== false) {
            $this->{$this->_key} = $key;
            $this->_db->where($this->_key, $key);
        }

        // UPDATE 수행
        $this->_db->update($this->_table);

        // 갱신된 행의 수 리턴
        return $this->_db->affected_rows();
    }

    /**
     * 데이터를 DELETE하고 삭제된 행의 수를 리턴한다.
     * @param  $key - WHERE절에서 '='연산으로 사용될 PK값.
     *                이 값을 생략하고 별도의 WHERE절을 구성할 수 있다.
     * @return integer
     */
    public function delete($key = false)
    {
        // 위에서 구현한 메서드를 사용하여 멤버변수를 db라이브러리에 설정
        $this->__init_fields();

        // 파라미터가 전달되었다면 Primary Key에 대한 WHERE절로 설정
        if ($key !== false) {
            $this->{$this->_key} = $key;
            $this->_db->where($this->_key, $key);
        }

        // DELETE 수행
        $this->_db->delete($this->_table);
        return $this->_db->affected_rows();
    }

    /**
     * 데이터를 조회한 후 결과를 리턴한다.
     * @param  $field   - 조회할 테이블 컬럼들 정보
     * @return object array
     */
    public function select($field = false)
    {
        /** 1) SELECT절에 사용할 컬럼이름 생성하기 */
        // 조회할 컬럼정보가 전달되지 않았다면 멤버변수를 스캔하여
        // SELECT에 사용할 컬럼이름들을 구성한다.
        if ($field === false) {
            $field = array();                   // SELECT절에서 사용할 컬럼을 저장할 배열
            $columns = get_object_vars($this);  // 현재 객체의 변수들을 배열로 변환

            foreach ($columns as $key => $value) {
                // 멤버변수에 `_`가 포함되지 않은 항목에 대해 field 배열에 추가
                if (substr($key, 0, 1) != '_') {
                    array_push($field, $key);
                }
            } // end foreach
        } // end if

        /** 2) 데이터 조회하기 */
        // 데이터 조회할 컬럼들 설정
        $this->_db->select($field);

        // 하위 클래스가 정의할 테이블 이름으로 데이터 조회
        $query = false;    
        if ($this->_is_from) {
            $query = $this->_db->get();    
        } else {
            $query = $this->_db->get($this->_table);
        }
        
        $row = array();                     // 조회결과를 담을 배열
        if ($query->num_rows() > 0) {       // 조회결과가 존재한다면?
            $row = $query->result();        // 결과를 배열로 추출
        }

        /** 3) 조회결과 리턴 */
        return $row;                        
    }


    /**
     * 단일행에 대한 데이터를 조회한 후 결과를 리턴한다.
     * @param  $key   - WHERE절에서 '='연산으로 사용될 PK값.
     *                  이 값을 생략하고 별도의 WHERE절을 구성할 수 있다.
     * @param  $field - 조회할 테이블 컬럼들 정보
     * @return object
     */
    public function select_one($key = false, $field = false)
    {
        // 키값이 전달되었다면 WHERE절의 조건값으로 사용한다.
        if ($key !== FALSE) {
            $this->_db->where($this->_key, $key);
        }

        // 데이터 조회
        $row = $this->select($field);

        // 조회 결과가 있다면 2차 배열 형식의 조회 결과에서 첫 번째 행만 추출한다.
        $result = false;
        if (count($row) > 0) {
            $result = $row[0];
        }

        return $result;
    }

    /**
     * SQL문에 활용할 테이블 이름을 별도로 지정한다.
     */
    public function from($from)
    {
        // 데이터베이스 라이브러리에 테이블 이름을 별도로 설정
        $this->_db->from($from);

        // 테이블 이름이 별도로 설정되었음을 확인
        $this->_is_from = TRUE;

        // 객체 스스로를 리턴하여 메서드 채인이 가능하도록 한다.
        return $this;
    }

    /**
     * 지금까지 라이브러리에 설정된 SQL문을 초기화 한다.
     */
    public function reset_query()
    {
        // 테이블 이름이 별도로 설정되었음을 취소함
        $this->_is_from = FALSE;
        $this->_db->reset_query();
        return $this;
    }

    //--- 이하 함수들은 CI의 기능들을 간접적으로 호출하기 위한 Wrapper 처리 ---
    public function where($key, $value = NULL, $escape = NULL)
    {
        $this->_db->where($key, $value, $escape);
        return $this;
    }

    public function join($table, $cond, $type = '', $escape = NULL)
    {
        $this->_db->join($table, $cond, $type, $escape);
        return $this;
    }

    public function or_where($key, $value = NULL, $escape = NULL)
    {
        $this->_db->or_where($key, $value, $escape);   
        return $this;
    }

    public function or_where_in($key = NULL, $values = NULL, $escape = NULL)
    {
        $this->_db->or_where_in($key, $values, $escape);
        return $this;
    }

    public function or_where_not_in($key = NULL, $values = NULL, $escape = NULL)
    {
        $this->_db->or_where_not_in($key, $values, $escape);
        return $this;
    }

    public function where_in($key = NULL, $values = NULL, $escape = NULL)
    {
        $this->_db->where_in($key, $values, $escape);
        return $this;
    }

    public function where_not_in($key = NULL, $values = NULL, $escape = NULL)
    {
        $this->_db->where_not_in($key, $values, $escape);
        return $this;
    }

    public function like($field, $match = '', $side = 'both', $escape = NULL)
    {
        $this->_db->like($field, $match, $side, $escape);
        return $this;
    }

    public function or_like($field, $match = '', $side = 'both', $escape = NULL)
    {
        $this->_db->or_like($field, $match, $side, $escape); 
        return $this;  
    }

    public function not_like($field, $match = '', $side = 'both', $escape = NULL)
    {
        $this->_db->not_like($field, $match, $side, $escape);
        return $this;
    }

    public function or_not_like($field, $match = '', $side = 'both', $escape = NULL)
    {
        $this->_db->or_not_like($field, $match, $side, $escape);
        return $this;
    }

    public function having($key, $value = NULL, $escape = NULL)
    {
        $this->_db->having($field, $value, $escape);
        return $this;
    }

    public function or_having($key, $value = NULL, $escape = NULL)
    {
        $this->_db->or_having($field, $value, $escape);
        return $this;
    }

    public function group_by($by, $escape = NULL)
    {
        $this->_db->group_by($by, $escape);
        return $this;
    }

    public function order_by($orderby, $direction = '', $escape = NULL)
    {
        $this->_db->order_by($orderby, $direction, $escape);
        return $this;
    }

    public function limit($value, $offset = 0)
    {
        $this->_db->limit($offset, $value);
        return $this;
    }

    public function offset($offset)
    {
        $this->_db->offset($offset);   
        return $this;
    }

    public function distinct($val = TRUE)
    {
        $this->_db->distinct($val);
        return $this;
    }

    public function set($key, $value = '', $escape = NULL)
    {
        $this->_db->set($key, $value, $escape);
        return $this;
    }

    public function select_avg($select = '', $alias = '')
    {
        return $this->_db->select_avg($select, $alias);
    }

    public function select_max($select = '', $alias = '')
    {
        return $this->_db->select_max($select, $alias);
    }

    public function select_min($select = '', $alias = '')
    {
        return $this->_db->select_min($select, $alias);
    }

    public function select_sum($select = '', $alias = '')
    {
        return $this->_db->select_sum($select, $alias);
    }

    public function count_all()
    {
        if ($this->_is_from) {
            return $this->_db->count_all();
        } else {
            return $this->_db->count_all($this->_table);
        }
    }

    public function count_all_results()
    {
        if ($this->_is_from) {
            return $this->_db->count_all_results();
        } else {
            return $this->_db->count_all_results($this->_table);
        }
    }

    public function last_query()
    {
        return $this->_db->last_query();
    }

    public function start_cache()
    {
        $this->_db->start_cache();
        return $this;
    }

    public function stop_cache()
    {
        $this->_db->stop_cache();
        return $this;
    }

    public function flush_cache()
    {
        $this->_db->flush_cache();
        return $this;
    }

    public function group_start()
    {
        $this->_db->group_start();
        return $this;
    }

    public function or_group_start()
    {
        $this->_db->or_group_start();
        return $this;   
    }

    public function not_group_start()
    {
        $this->_db->not_group_start();
        return $this;
    }

    public function or_not_group_start()
    {
        $this->_db->or_not_group_start();
        return $this;
    }

    public function group_end()
    {
        $this->_db->group_end();
        return $this;
    }

    public function trans_start()
    {
        return $this->_db->trans_start();
    }

    public function trans_commit()
    {
        return $this->_db->trans_commit();
    }

    public function trans_rollback()
    {
        return $this->_db->trans_rollback();
    }
}



