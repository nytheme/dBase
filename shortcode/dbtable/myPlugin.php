<?php 
require_once(__DIR__ . '/config/config.php');
$dbtable = new \Dbtable\Model();
$plugins = $dbtable->getAll();
?>

<div id="modal-register" uk-modal >
    <div class="uk-modal-dialog"style="background:#222222;">    
        <button id="registerModalClose" class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-body" uk-overflow-auto>
            <form>
                <fieldset class="uk-fieldset">
                    <legend class="uk-legend">Regisiter New Plugin</legend>
                    <div class="uk-margin">
                        <input id="company" class="uk-input" type="text" placeholder="Company">
                    </div>
                    <div class="uk-margin">
                        <input id="name" class="uk-input" type="text" placeholder="Name">
                    </div>
                    <div class="uk-margin">
                        <select id="category" class="uk-select">
                            <option value="Channel Strip">Channel Strip</option>
                            <option value="EQ">EQ</option>
                            <option value="Compressor">Compressor</option>
                            <option value="Reverb">Reverb</option>
                            <option value="Delay">Delay</option>
                            <option value="Other">Other</option>
                            <option value="Mastering">Mastering</option>
                        </select>
                    </div>
                    <div class="uk-margin">
                        <textarea id="memo" class="uk-textarea" rows="5" placeholder="Memo
                        "></textarea>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-primary uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" type="button" id="register">Save</button>
        </div>
    </div>
</div><!--modal-register-->
<div id="modal-edit" uk-modal >
    <div class="uk-modal-dialog"style="background:#222222;">    
        <button id="editModalClose" class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-body" uk-overflow-auto>
            <form>
                <fieldset class="uk-fieldset">
                    <legend class="uk-legend">Edit Plugin</legend>
                    <div class="uk-margin">
                        <input id="edit-company" class="uk-input" type="text" placeholder="Company">
                    </div>
                    <div class="uk-margin">
                        <input id="edit-name" class="uk-input" type="text" placeholder="Name">
                    </div>
                    <div class="uk-margin">
                        <select id="edit-category" class="uk-select">
                            <option value="Channel Strip">Channel Strip</option>
                            <option value="EQ">EQ</option>
                            <option value="Compressor">Compressor</option>
                            <option value="Reverb">Reverb</option>
                            <option value="Delay">Delay</option>
                            <option value="Other">Other</option>
                            <option value="Mastering">Mastering</option>
                        </select>
                    </div>
                    <div class="uk-margin">
                        <textarea id="edit-memo" class="uk-textarea" rows="5" placeholder="Memo
                        "></textarea>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-primary uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" type="button" id="edit">Save</button>
        </div>
    </div>
</div><!--modal-revision-->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script>
    //新規登録
    $('#register').on('click', function() {
        console.log('submit!');
        let company  = $('#company').val();
        let name     = $('#name').val();
        let category = $('#category').val();
        let memo     = $('#memo').val();

        $.post('/wp-content/themes/dBase/shortcode/dbtable/lib/Controller.php', {
            company:  company,
            name:     name,
            category: category,
            memo:     memo,
            mode:    'register'
        }, function(res) {
            // フォームのクリア
            // $("#company").val("").focus()
            // $("#name").val("");
            // $("#category").val("");
            // $("#memo").val("");
        });
        //return false; //画面遷移を防
        
        //新規登録後テーブルに表示される要素「td」の作成
        var tableTr    = document.createElement("tr");
        var companyTd  = document.createElement("td");
        var nameTd     = document.createElement("td");
        var categoryTd = document.createElement("td");
        var memoTd     = document.createElement("td");
        var editTd     = document.createElement("td");
        // editTd のリンク（ aタグ ）を作成
        var editLink   = document.createElement("a");
        editLink.setAttribute("href","#modal-edit");
        editLink.setAttribute("uk-toggle","");
        editLink.classList.add("uk-icon-link");
        editLink.classList.add("uk-margin-small-right");
        editLink.setAttribute("uk-icon","file-edit");
        editLink.setAttribute("onclick","editButton(event)");
        editLink.setAttribute("data-company",company);
        editLink.setAttribute("data-name",name);
        editLink.setAttribute("data-category",category);
        editLink.setAttribute("data-memo",memo);
        
        companyTd.textContent  = company;
        nameTd.textContent     = name;
        categoryTd.textContent = category;
        memoTd.textContent     = memo;
        editTd.appendChild( editLink );
        
        tableTr.appendChild( companyTd );
        tableTr.appendChild( nameTd );
        tableTr.appendChild( categoryTd );
        tableTr.appendChild( memoTd );
        tableTr.appendChild( editTd );

        // console.log(companyTd);
        // console.log(nameTd);
        // console.log(categoryTd);
        // console.log(memoTd);
        // console.log(editTd);
        // console.log(tableTr);

        // id属性で要素を取得
        let textbox_element = document.getElementById('dbtableTh');    
        // 指定した要素の中の末尾に挿入
        textbox_element.after(tableTr);
        // モーダルを閉じるためにクリックさせる
        document.getElementById("registerModalClose").click();

    });

    function editButton(e) { // e はHTMLの onclick=editButton(event)の引数。eventの「e」かな？
        let dataCompany  = e.currentTarget.getAttribute('data-company')
        let dataName     = e.currentTarget.getAttribute('data-name')
        let dataCategory = e.currentTarget.getAttribute('data-category')
        let dataMemo     = e.currentTarget.getAttribute('data-memo')
        let nameForSQL   = e.currentTarget.closest('tr').children[1].textContent;
        document.getElementById('edit-company').value = dataCompany;
        document.getElementById('edit-name').value = dataName;
        document.getElementById('edit-category').value = dataCategory;
        document.getElementById('edit-memo').value = dataMemo;
        //　SQL検索用の変数を保存。詳しくは「let nameForSQL」行でコメント
        sessionStorage.setItem( 'nameForSQL', nameForSQL ); // sessionStorage.setItem('key', 'value')
	};

    //編集
    $('#edit').on('click', function() {
        console.log('submit!');
        let company  = $('#edit-company').val();
        let name     = $('#edit-name').val();
        let category = $('#edit-category').val();
        let memo     = $('#edit-memo').val();

        // 新規登録プラグインを画面切り替えせずに編集するためにプラグイン名でWHEREを書く.
        // 通常はIDを使用するが、新規登録して表示されたプラグインは画面遷移せずにIDを取得できないので、名前をID代わりに使う
        // 名前を変更した場合「WHERE name = :name」で齟齬が生じるので、変更前のプラグイン名を「nameForSQL」として別個に使用し、「WHERE name = :nameForSQL」とする
        let nameForSQL = sessionStorage.getItem('nameForSQL');
        
        $.post('/wp-content/themes/dBase/shortcode/dbtable/lib/Controller.php', {
            company:    company,
            name:       name,
            nameForSQL: nameForSQL,
            category:   category,
            memo:       memo,
            mode:      'edit'
        }, function(res) {

        });
        // モーダルを閉じるためにクリックさせる
        document.getElementById("editModalClose").click();

        // Table書き換え
        document.getElementById("company_" + name).textContent = company;
        // document.getElementById("name_" + name).textContent = name;
        document.getElementById("category_" + name).textContent = category;
        document.getElementById("memo_" + name).textContent = memo;     

    });
</script>

<div style="display:flex;justify-content:space-between;height:30px;">
    <a class="uk-button uk-button-default uk-button-small" href="#modal-register" uk-toggle>Register</a>
    <form action="" method="post">
        <div class="uk-margin" uk-margin>
            <select class="uk-select uk-form-width-medium uk-form-small" name="categoryFilter">
                <option value="">All</option>
                <option value="Channel Strip">Channel Strip</option>
                <option value="EQ">EQ</option>
                <option value="Compressor">Compressor</option>
                <option value="Reverb">Reverb</option>
                <option value="Delay">Delay</option>
                <option value="Other">Other</option>
                <option value="Mastering">Mastering</option>
            </select>
            <button type="button" class="uk-button uk-button-default uk-form-small" id="filterButton">Filter</button>
        </div>
    </form>
</div>

<table class="uk-table uk-table-small uk-table-divider" id="dbtable">
    <thead>
        <tr id="dbtableTh">
            <th>Company</th>
            <th>name</th>
            <th>category</th>
            <th>memo</th>
            <th>edit</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $plugins as $plugin ): ?>
            <tr>
                <td id="company_<?php echo $plugin->name; ?>"><?php echo $plugin->company; ?></td>
                <td id="name_<?php echo $plugin->name; ?>" class="pluginName"><?php echo $plugin->name; ?></td>
                <td id="category_<?php echo $plugin->name; ?>"><?php echo $plugin->category; ?></td>
                <td id="memo_<?php echo $plugin->name; ?>"><?php echo $plugin->memo; ?></td>
                <td>
                    <a href="#modal-edit" uk-toggle class="uk-icon-link uk-margin-small-right" uk-icon="file-edit" 
                    data-company= "<?php echo $plugin->company; ?>"
                    data-name=    "<?php echo $plugin->name; ?>"
                    data-category="<?php echo $plugin->category; ?>"
                    data-memo=    "<?php echo $plugin->memo; ?>"
                    onclick="editButton(event)"></a>
                </td>
            </tr>
        <?php endforeach; ?>   
        <tr> <!-- 最終段にボーダーラインとスペースをつくるため -->
            <td></td><td></td><td></td><td></td><td></td>
        </tr> 
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
const button = document.getElementById('filterButton');
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