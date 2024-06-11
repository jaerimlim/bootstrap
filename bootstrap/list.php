
<?php
  include("./dbconn.php"); //데이터베이스 연결

?>




<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>게시글 내용</title>
    <!-- 초기화 -->
    <link rel="stylesheet" type="text/css" href="../css/reset.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <!-- 부트스트랩 css파일 연결하기  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- 부트스트랩 js파일 연결하기 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" ></script> 

  <style>


    /* write_list서식 */
    section{
      max-width: 1200px;
      margin: 100px auto;
      text-align:center;
      border-collapse: collapse;
      
    }
    .write_list{
      width:100%
    }
    .search_box{
      float: right;
      margin: 10px 0px
    }
    .write_list .search_box input:first-child{
      width: 250px;
      height: 30px;
      border:1px solid #ccc;

    }
    .write_list .search_box input:last-child{
      height: 34px;
      box-sizing: border-box;
      border: none;
      background: #032852;
      color:#fff;
      transform:translateY(2px);
      cursor: pointer;
    }

    .write_list table tr, th, td{
      border: 1px solid #ccc;
      line-height: 40px;
      font-size: 14px;
    }
    th{
      line-height: 40px;
      background: #032852;
      color:#fff;
    }
    td{
      border-left:none;
    }

    .write_list th:nth-child(2){
      width: 50%;
      text-overflow: ellipsis; /*...이 나오게함*/
      white-space: nowrap; /*다음 줄로 넘기지 않기( 한줄로)*/
      overflow: hidden;
    }
    .write_list td:nth-child(2){
      text-align:left;
      text-indent: 20px
    }
    .write_list th:first-child{
      border-left:none;
    }
    .write_list th:last-child{
      width: 20%;
      border-right:none;
    }
    .write_list td:last-child{
      border-right:none;
    }
    section .write_btn{
      float:right;
    }
    section .write_btn a{
      font-size: 14px;
      display: block;
      padding: 10px 30px;
      background: #ccc;
      margin: 10px 0px;
      
    }
    .pagination{
      margin: 20px 0px;
    }
    .pagination a{
      font-size: 14px;
      padding: 10px 10px;
      display: inline-block
    }
  </style>
</head>
<body>



    <section>
    <form name="검색하기" method="post" action="./search.php" class="">
    <div class="search_box input-group  justify-content-end">
          <input type="text" id="search_txt" name="search_txt"  placeholder="search" class="input-group-text">
          <input type="submit" value="검색" id="search_btn" onclick="return form_check()" class="input-group-text">
    </div>

    <table class="write_list table  table-hover">
        <tr class="table-dark">
          <th>NO.</th>
          <th>제목</th>
          <th>작성자</th>
          <th>날짜</th>
        </tr>

      <?php
        $num = 50;
        //한 페이지에 보여질 게시물 개수
        $list_num = 5;
        
        //이전, 다음 버튼 클릭시 나오는 페이지 수
        $page_num =3;
        
        //현재 페이지
        $page = isset($_GET["page"])? $_GET["page"] : 1;
        
        // 전체페이지수 계산
        $total_page = ceil($num / $list_num);
        //10페이지 = 게시물 50개 / 5 한페이지 출력개수
        
        //전체블럭 계산
        $total_block = ceil($total_page / $page_num);
        //3.333333 =  10/3
        
        //현재블럭번호 계산
        $now_block = ceil($page / $page_num);
        
        //블럭당 시작페이지 번호
        $s_pageNum = ($now_block - 1) * $page_num + 1;
        
        //데이터가 0인 경우
        if($s_pageNum <= 0){ $s_pageNum = 1; };
        
        //블럭당 마지막페이지 번호
        $e_pageNum = $now_block * $page_num;
        
        //마지막 번호가 전체 페이지번호보다 크다면 동일한 값을 준다.
        if($e_pageNum > $total_page){ $e_pageNum = $total_page; };

        $start = ($page - 1) * $list_num;
        $cnt = $start + 1;   

    
        $query = "select * from free_board ORDER BY id limit $start, $list_num;";
        $result = mysqli_query($conn, $query);
  
        //반복문 while
        while($row = mysqli_fetch_array($result)){
      ?>
      
      <tr>
          <td><?=$row['id']?></td>
          <td><a href="view.php?id=<?=$row['id']?>" title="<?=$row['subject']?>">
              <?=$row['subject']?>
            </a></td>
          <td><?=$row['name']?></td>
          <td>
            <?=date("Y-m-d",strtotime($row['datetime']))?>
          </td>
          <!--substr($date['datetime'],0,10)-->
      </tr>
            
      <?php
          }
            $cnt++;


          mysqli_free_result($result);
          mysqli_close($conn);
      ?>
    </table>


    <nav aria-label="페이지네이션">
      <ul class="pagination justify-content-center">
        <?php 
          //페이지네이션이 들어가는 곳
          //이전페이지
          if($page-3 <= 1){ ?> 
            <li class="page-item"><a href="list.php?page=1" class="page-link">이전</a> </li>
            <?php } 
            else{ ?> 
            <li class="page-item"><a href="list.php?page=<?php echo ($page-3); ?>" class="page-link">이전</a></li>
            <?php };
            ?> 
        <?php //여기서부터 페이지 번호출력하기
          for($print_page=$s_pageNum;$print_page<=$e_pageNum;$print_page++){?>
            <li class="page-item"><a href="list.php?page=<?php echo $print_page; ?>" class="page-link">
              <?php echo $print_page ?>
            </a></li>
          <?php }; ?>  

          <!-- 다음 버튼 나오는 곳 -->
          <?php if($page+3 >=$total_page){ ?>
            <li class="page-item"><a href="list.php?page=<?php echo $total_page; ?>" title="다음페이지로" class="page-link">다음</a></li>
          <?php }else{ ?>
            <li class="page-item"><a href="list.php?page=<?php echo ($page+3); ?>" class="page-link">다음</a></li>
        <?php };    
        ?>
      </ul>  
    </nav>
      <div class="input-group  justify-content-end">
        <a href="write.php" title="글쓰기" class="btn btn-primary "> 글쓰기</a>

      </div>

    </form>
    
  
  </section>
  </main>


  <script>

    // let s_btn = document.getElementById('search_btn');
    // s_btn.addEventListener('click', function(){
    //   form_check();
    // });

    function form_check (){
      if(document.getElementById('search_txt').value.length<1){
        alert('검색어를 입력하지 않았습니다. 확인하세요.');
        return false;
      }
      return true;
    };

  </script>
</body>
</html>