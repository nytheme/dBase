<?php 
require_once(__DIR__ . '/config/config.php');
$dbtable = new \Dbtable\Model();
$plugins = $dbtable->getAll();
?>
<form action="" method="post">
    <div class="uk-margin" uk-margin>
        <select class="uk-select uk-form-width-medium" name="categoryFilter">
            <option value="Channel Strip">Channel Strip</option>
            <option value="EQ">EQ</option>
            <option value="Compressor">Compressor</option>
            <option value="Reverb">Reverb</option>
            <option value="Delay">Delay</option>
            <option value="Other">Other</option>
            <option value=""></option>
            <option value="Mastering">Mastering</option>
        </select>
        <button type="button" class="uk-button uk-button-default" id="button">Filter</button>
    </div>
</form>
<table class="uk-table uk-table-small uk-table-divider" id="dbtable">
    <thead>
        <tr>
            <th>Company</th>
            <th>name</th>
            <th>category</th>
            <th>memo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $plugins as $plugin ): ?>
            <tr>
                <td><?php echo $plugin->company; ?></td>
                <td><?php echo $plugin->name; ?></td>
                <td><?php echo $plugin->category; ?></td>
                <td><?php echo $plugin->memo; ?></td>
            </tr>
        <?php endforeach; ?>    
    </tbody>
</table>

<script>
//テーブルのソート
    //thがクリックされたら、sortRows()が呼ばれる
    document.querySelectorAll('th').forEach(th => th.onclick = sortRows);
    //sortRows()の中では、まずtableの各行から「その行への参照」と「クリックされた列の値」をセットにしたレコードオブジェクトを作って、ソート用の配列にぶち込む
    function sortRows() {
        const table = document.querySelector("table");
        const records = [];
        for (let i = 1; i < table.rows.length; i++) {
            const record = {};
            record.row = table.rows[i];
            record.key = table.rows[i].cells[this.cellIndex].textContent;
            records.push(record);
        }
        //ソート用配列をソート
        records.sort(compareKeys);
        //キー比較関数(コンパレータ)。レコードオブジェクトからソート用の値を取り出して、どっちが大きいか比較した結果を返す
        function compareKeys(a, b) {
            if (a.key < b.key) return -1;
            if (a.key > b.key) return 1;
            return 0;
        }
        //ソートが終わったらソート後の順番で行をtableに書き戻す。appendChild()は、対象がすでにtableの子要素だった場合、removeしてからappendしてくれるので、結果的に行が「移動」することになる。
        for (let i = 0; i < records.length; i++) {
            table.appendChild(records[i].row);
        }
    }

//テーブルのフィルター
const button = document.getElementById('button');
button.onclick = filterRows;
const table = document.getElementById('dbtable');
const formElements = document.querySelector("select[name=categoryFilter]");
function filterRows() {
    // console.log(formElements.value);
    const regex = new RegExp(formElements.value, 'i');
    //tableの各行を取り出し、いったん非表示にする。
    for (let i = 1; i < table.rows.length; i++) {
        const row = table.rows[i];
        row.style.display = 'none';
        //その行の各列の値を正規表現と比較し、1つでもマッチしたら、その行が表示されるようにする。
        for (let j = 0; j < row.cells.length; j++) {
            if (row.cells[j].textContent.match(regex)) {
                row.style.display = 'table-row';
                break;
            }
        }
    }
}
</script>