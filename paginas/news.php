<?
  $msg_erro = "<br>";
  if($pg_include_manutencao){
    include("manutencao.html");
    exit;
  }
  echo '<style>
          h3 {
              margin-top: 5px;
              text-align: center;
              color: #069e52;
          }

          div.title {
              margin-top: 50px;
          }

          div.news {
              margin-top: 30px;
              margin-bottom: 50px;
          }

          iframe:focus {
              outline: none;
          }

          iframe[seamless] {
              display: block;
          }

          iframe {
              overflow: hidden;
          }
          .rssincl-entry{
            background-color: red;
          }
      </style>
      <!-- /CSS !-->
      <h1><b>EcoEletri Notícias</b></h1>
      <h3><i> por Google</i></h3>
      </div>
      <div class="box_divisoria" align="center">
          <!--<iframe width="90%" height="4850px" scrolling="no" src="http://g1.globo.com/economia/negocios/">!-->
          <iframe  width="1050" height="900" style="border:none;" scrolling="no" src="http://output18.rssinclude.com/output?type=iframe&amp;id=1085759&amp;hash=297d42a267827b358e5f37be3d9f60b7"></iframe>
      </div>';
?>
