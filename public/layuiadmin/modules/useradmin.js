/**

 @Name：layuiAdmin 用户管理 管理员管理 角色管理
 @Author：star1029
 
 @License：LPPL
 */
layui.define(['table', 'form'], function (exports) {
  var $ = layui.$
    , table = layui.table
    , form = layui.form
    , layer = layui.layer;
  //用户管理
  table.render({
    elem: '#LAY-user-manage'
    , url: '/admin/api/users/adminLikeUserListByIdOrNameOrEmail'
    , headers: {
      authorization: "Bearer " + token,
    }
    , cols: [[
      { type: 'checkbox', fixed: 'left' }
      , { field: 'id', width: 100, title: 'ID', sort: true }
      , { field: 'name', title: '用户名', minWidth: 100 }
      , { field: 'avatar', title: '头像', width: 100, templet: '#imgTpl' }
      , { field: 'phone', title: '手机' }
      , { field: 'email', title: '邮箱' }
      , { field: 'createTime', title: '加入时间', sort: true }
      , {
        field: 'status', title: '状态', sort: true, templet: function (data) {
          return data.status == 1 ? '正常' : '禁用'
        }
      }
      , { title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-useradmin-webuser' }
    ]]
    , page: true
    , limit: 10
    , height: 'full-220'
    , text: '对不起，加载出现异常！'
  });

  //监听工具条
  table.on('tool(LAY-user-manage)', function (obj) {
    var data = obj.data;
    if (obj.event === 'del') {
      layer.prompt({
        formType: 1
        , title: '敏感操作，请验证口令'
      }, function (value, index) {
        layer.close(index);
        console.log(value)
        layer.confirm('真的删除行么', function (index) {
          obj.del();
          layer.close(index);
        });
      });
    } else if (obj.event === 'edit') {
      var tr = $(obj.tr);
      id = data.id;
      layer.open({
        type: 2
        , title: ['编辑用户']
        , content: '/user/user/userform'
        , maxmin: true
        , area: ['500px', '700px']
        , btn: ['确定', '取消']
        , yes: function (index, layero) {
          var iframeWindow = window['layui-layer-iframe' + index]
            , submitID = 'LAY-user-front-submit'
            , submit = layero.find('iframe').contents().find('#' + submitID);
          //监听提交
          iframeWindow.layui.form.on('submit(' + submitID + ')', function (data) {
            layer.confirm('确认修改？', function (index_index) {
              layer.msg('修改中', { icon: 16 })
              var field = data.field; //获取提交的字段
              //提交 Ajax 成功后，静态更新表格中的数据
              //$.ajax({});
              var status = field.status
              if (status) {
                status = 1
              } else {
                status = 0
              }
              $.ajax({
                url: '/admin/api/users/adminUpdateUserById',
                type: 'POST',
                headers: {
                  authorization: "Bearer " + token,
                },
                data: {
                  'id': id,
                  'name': field.username,
                  'email': field.email,
                  'phone': field.phone,
                  'authority': field.role,
                  'avatar': field.image,
                  'status': status,
                },
                success: function (data) {
                  if (data.code == 0) {
                    layer.msg(data.msg, { icon: 6 })
                  } else {
                    layer.msg(data.msg, { icon: 5 })
                  }
                }
              })
              layer.close(index); //关闭弹层
              var dt = setInterval(() => {
                table.reload('LAY-user-manage'); //数据刷新
                clearInterval(dt);
              }, 500);
              layer.close(index_index);
            });
          });
          submit.trigger('click');
        }, btn2: function (index, layero) {
          layer.confirm('确定要关闭么', function (index_index) {
            layer.close(index);
            layer.close(index_index);
          });
          // 开启该代码可禁止点击该按钮关闭
          return false
        }, cancel: function (index, layero) {
          layer.confirm('确定要关闭么', function (index_index) {
            layer.close(index);
            layer.close(index_index);
          });
          // 开启该代码可禁止点击该按钮关闭
          return false
        }
      });
    }
  });

  //管理员管理
  table.render({
    elem: '#LAY-user-back-manage'
    , url: '/admin/api/users/adminLikeAdminListByIdOrNameOrEmail' //模拟接口
    , headers: {
      authorization: "Bearer " + token,
    }
    , cols: [[
      { type: 'checkbox', fixed: 'left' }
      , { field: 'id', width: 100, title: 'ID', sort: true }
      , { field: 'name', title: '用户名', minWidth: 100 }
      , { field: 'avatar', title: '头像', width: 100, templet: '#imgTpl' }
      , { field: 'phone', title: '手机' }
      , { field: 'email', title: '邮箱' }
      , { field: 'createTime', title: '加入时间', sort: true }
      , {
        field: 'status', title: '状态', sort: true, templet: function (data) {
          return data.status == 1 ? '正常' : '禁用'
        }
      }
    ]]
    , page: true
    , limit: 10
    , height: 'full-220'
    , text: '对不起，加载出现异常！'
  });

  //监听工具条
  table.on('tool(LAY-user-back-manage)', function (obj) {
    var data = obj.data;
    if (obj.event === 'del') {
      layer.prompt({
        formType: 1
        , title: '敏感操作，请验证口令'
      }, function (value, index) {
        layer.close(index);
        layer.confirm('确定删除此管理员？', function (index) {
          console.log(obj)
          obj.del();
          layer.close(index);
        });
      });
    } else if (obj.event === 'edit') {
      var tr = $(obj.tr);

      layer.open({
        type: 2
        , title: '编辑管理员'
        , content: '../../../views/user/administrators/adminform.html'
        , area: ['420px', '420px']
        , btn: ['确定', '取消']
        , yes: function (index, layero) {
          var iframeWindow = window['layui-layer-iframe' + index]
            , submitID = 'LAY-user-back-submit'
            , submit = layero.find('iframe').contents().find('#' + submitID);

          //监听提交
          iframeWindow.layui.form.on('submit(' + submitID + ')', function (data) {
            var field = data.field; //获取提交的字段

            //提交 Ajax 成功后，静态更新表格中的数据
            //$.ajax({});
            table.reload('LAY-user-back-manage'); //数据刷新
            layer.close(index); //关闭弹层
          });

          submit.trigger('click');
        }
        , success: function (layero, index) {

        }
      })
    }
  });

  //角色管理
  table.render({
    elem: '#LAY-user-back-role'
    , url: layui.setter.base + 'json/useradmin/role.js' //模拟接口
    , cols: [[
      { type: 'checkbox', fixed: 'left' }
      , { field: 'id', width: 80, title: 'ID', sort: true }
      , { field: 'rolename', title: '角色名' }
      , { field: 'limits', title: '拥有权限' }
      , { field: 'descr', title: '具体描述' }
      , { title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-useradmin-admin' }
    ]]
    , text: '对不起，加载出现异常！'
  });

  //监听工具条
  table.on('tool(LAY-user-back-role)', function (obj) {
    var data = obj.data;
    if (obj.event === 'del') {
      layer.confirm('确定删除此角色？', function (index) {
        obj.del();
        layer.close(index);
      });
    } else if (obj.event === 'edit') {
      var tr = $(obj.tr);

      layer.open({
        type: 2
        , title: '编辑角色'
        , content: '../../../views/user/administrators/roleform.html'
        , area: ['500px', '480px']
        , btn: ['确定', '取消']
        , yes: function (index, layero) {
          var iframeWindow = window['layui-layer-iframe' + index]
            , submit = layero.find('iframe').contents().find("#LAY-user-role-submit");

          //监听提交
          iframeWindow.layui.form.on('submit(LAY-user-role-submit)', function (data) {
            var field = data.field; //获取提交的字段

            //提交 Ajax 成功后，静态更新表格中的数据
            //$.ajax({});
            table.reload('LAY-user-back-role'); //数据刷新
            layer.close(index); //关闭弹层
          });

          submit.trigger('click');
        }
        , success: function (layero, index) {

        }
      })
    }
  });

  exports('useradmin', {})
});