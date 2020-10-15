<script  type="text/javascript">
jQuery(function($){

    $(".c-carousel-button-right").click(function(){ // при клике на правую кнопку запускаем следующую функцию:
        $(".z-carousel-items").animate({left: "-142px"}, 200); // производим анимацию: блок с набором картинок уедет влево на 222 пикселя (это ширина одного прокручиваемого элемента) за 200 милисекунд.
        setTimeout(function () { // устанавливаем задержку времени перед выполнением следующих функций. Задержка нужна, т.к. эти ффункции должны запуститься только после завершения анимации.
            $(".z-carousel-items .c-carousel-block").eq(0).clone().appendTo(".z-carousel-items"); // выбираем первый элемент, создаём его копию и помещаем в конец карусели
            $(".z-carousel-items .c-carousel-block").eq(0).remove(); // удаляем первый элемент карусели
            $(".z-carousel-items").css({"left":"0px"}); // возвращаем исходное смещение набора набора элементов карусели
        }, 300);
    });

    $(".c-carousel-button-left").click(function(){ // при клике на левую кнопку выполняем следующую функцию:
        $(".z-carousel-items .c-carousel-block").eq(-1).clone().pprependTo(".z-carousel-items"); // выбираем последний элемент набора, создаём его копию и помещаем в начало набора
        $(".z-carousel-items").css({"left":"-142px"}); // устанавливаем смещение набора -222px
        $(".z-carousel-items").animate({left: "0px"}, 200); // за 200 милисекунд набор элементов плавно переместится в исходную нулевую точку
        $(".z-carousel-items .c-carousel-block").eq(-1).remove(); // выбираем последний элемент карусели и удаляем его
    });

});

</script>

   <div class="c-carousel"> <!-- контейнер, в котором будет карусель -->

<div class="c-carousel-button-left"></div> <!-- левая кнопка -->
<div class="c-carousel-button-right"></div> <!-- правая кнопка -->
<div class="z-carousel-wrapper"> <!-- видимая область карусели -->
<div class="z-carousel-items"> <!-- весь набор элементов карусели -->
<?php foreach($last_prod as $curr){ ?>
  <?php if ($curr->label_id == '6') continue; ?>
  <?php if ($curr->product_price == '0') continue; ?>
     <div class="c-carousel-block">
     <div class="block_item">
     <div class="item_name">
            <a href="<?php print $curr->product_link?>"><?php 
			$str=($curr->name);
            //разбиваем на массив
             $arr_str = explode(" ", $str);
            //берем первые 6 элементов
            $arr = array_slice($arr_str, 0, 6);
            //превращаем в строку
            $new_str = implode(" ", $arr);
           // Если необходимо добавить многоточие
            if (count($arr_str) > 6) {
            $new_str .= '...';
            }
            echo $new_str;//Выведет 'Этот текст имеет большое количество пробелов и...'
			?></a>
        </div>
       <?php if ($show_image) { ?>
       <div class="item_image">
           <a href="<?php print $curr->product_link?>"><img width="70px" height="70px" src = "<?php print $jshopConfig->image_product_live_path?>/<?php if ($curr->product_thumb_image) print $curr->product_thumb_image; else print $noimage?>" alt="" /></a>
       </div>
	   <div class="cls"></div>
       <?php } ?>
            <?php if ($curr->_display_price){?>
        <?php if ($curr->product_price !=0) {?>
       <div class="item_price">
            <div class="item_price_1">
			<?php if ($curr->product_price == '0') { ?>
			<span style="font-size:8px;"><?php print 'ДОГОВОРНАЯ ЦЕНА';?></span>
			<?php } else {?>
			<?php print formatprice($curr->product_price);?>
			<?php }?>
			</div>
       
	          <div style="padding:0;  text-align: center;"><a class="button_buy" title="Добавить в корзину <?php 
			$str=($curr->name);
            //разбиваем на массив
             $arr_str = explode(" ", $str);
            //берем первые 6 элементов
            $arr = array_slice($arr_str, 0, 6);
            //превращаем в строку
            $new_str = implode(" ", $arr);
           // Если необходимо добавить многоточие
            if (count($arr_str) > 6) {
            $new_str .= '...';
            }
            echo $new_str;//Выведет 'Этот текст имеет большое количество пробелов и...'
			?>" href="index.php?option=com_jshopping&controller=cart&task=add&category_id=<?php print $curr->category_id?>&product_id=<?php print $curr->product_id?>">В корзину</a>
			</div>
			</div>
		<?php }?>
		<div class="cls"></div>
        <?php }?>
   </div>  
   </div>   
<?php } ?>
<div class="cls"></div>
</div>
</div>
</div>