<?php


class UserBundleListTemplate {
    public function pagePanel ($action, $userRequest, $countPage, $openPage) {
        $action .= '&sorting='.$userRequest['sorting'].'&direction='.$userRequest['direction'];
        $element = '
        <div class="pagePanel">
            <div class="fullPanel">
                
                <div class="textPanel">
                    &nbsp;'.$openPage.'&nbsp;
                </div>
                <div class="darkPanel"> 
                    <form action="'.$action.'" method = "GET">
                    <input type="hidden" name="action" value = "userList">
                    <input type="hidden" name="sorting" value = "'.$userRequest['sorting'].'">
                    <input type="hidden" name="direction" value = "'.$userRequest['direction'].'">
                    <input type="text" name="page" size="3" maxlength="5" class="textLabel">
                    </form>
                ';
            if ($countPage > 7)
            {
                for ($i = $countPage; $i>$countPage-3; $i--)
                    $element .=  '<a href = "'.$action.'&page='.$i.'" class="btSml">'.$i.'</a>';
                $element .=  '<a class="dotsSml">...</a>';
                for ($i = 3; $i>0; $i--)
                    $element .=  '<a href = "'.$action.'&page='.$i.'" class="btSml">'.$i.'</a>';
            }
            else
                for ($i = $countPage; $i>0; $i--)
                    $element .= '<a href = "'.$action.'&page='.$i.'" class="btSml">'.$i.'</a>';
            $element .= '<div class="strelki">';
            if ($openPage>1) $element .= '<a href = "'.$action.'&page='.($openPage-1).'" class="btSml">◄</a>';
            if ($openPage<$countPage) $element .= '<a href = "'.$action.'&page='.($openPage+1).'" class="btSml">►</a>';
        $element .= 	'</div>
            
            <div style="clear:both;"></div>
                    </div>
                </div>
            </div>
	    ';
        return $element;
    }

    public function table($contain) {
        return "<table>".$contain."</table>";
    }

    public function tableHead($contain, $action) {
        return "<td><a href='".$action."'>".$contain."</a></td>";
    }

    public function row($contain, $action) {
        $element = "<tr";
        if ($action) {
            $element .= " style=\"background-color:#ECECEC;\"
                onMouseOver=\"this.style.backgroundColor='white';\"
                onMouseOut=\"this.style.backgroundColor='#ECECEC'\"
                onclick=\"location.href='".$action."'\" ";
        }
        $element .=">".$contain."</tr>\n";
        return $element;
    }

    public function cell($contain) {
        return "<td>".$contain."</td>";
    }
}