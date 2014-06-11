<div id="data">
			<table cellpadding="0" cellspacing="2">
				<tr>
					<th>column 1</th>
					<th>column 2</th>
					<th>column 3</th>
                                        <th>column 4</th>
				</tr>
                                <? echo getList($currentpage, $highlight); ?>
                        </table>
                        </div>
			
<p>
<form method="link" action="javascript:document.location.reload()"><input type="submit" value="update" 
onClick="this.value = 'updating...'"></form></p>