
@section('extratabs')
  <li><a href="#pages" role="tab" data-toggle="tab">Pages</a></li>
@overwrite

@section('extendedgeneral')

@overwrite

@section('extratabcontainter')

<div id="pages" class="tab-pane">

      <!-- Labels -->
      <div class="form-group">
          <?php
            // *Very simple* recursive rendering function
            function renderNode($node, $pageOptionValuesSelected = array()) {
              echo '<li class="language-'.$node->language_id.' depth-'.$node->depth.'  ">';
              echo '<span>';
              if($node->depth == 0) echo $node->title .'<i class="fa fa-plus-square"></i>';
              else {
                $checked = false;
                if(in_array($node->id,$pageOptionValuesSelected)) $checked = true;
                ?>
                  {!! Form::checkbox("page_id[".$node->language_id."][".$node->id."]", $node->id, $checked, array('class' => 'form-checkbox','id'=>'page_id-'.$node->id))  !!}
                  {!! Form::label('page_id-'.$node->id, $node->title, array('class' => ($checked == true?'active':'').' checkbox','id'=>'chkbxpage_id-'.$node->id)) !!}
                <?php
              }
              echo '</span>';

              if ( $node->children()->count() > 0 ) {
                echo "\r\n".'<ul class="'.($node->depth==0?'division':($node->depth==1?'sector':'subsector')).'">'."\r\n";
                foreach($node->children as $child) renderNode($child, $pageOptionValuesSelected);
                echo '</ul>'."\r\n"."\r\n";
              }
              echo '</li>'."\r\n";
            }

            $roots = \Dcms\Pages\Models\Pages::roots()->get();

            echo '<ul class="country">';
            foreach($roots as $root) {
                renderNode($root, $model["pageOptionValuesSelected"]);
            }
            echo '</ul>';
          ?>
      </div>
</div>

@overwrite

<script language="javascript" type="application/javascript">

$(document).ready(function() {
  //pagetree
  $(".country span").click(function() {
    $(this).find('i').toggleClass('fa-minus-square');
    $(this).next().toggleClass('active');
  });
});

</script>
