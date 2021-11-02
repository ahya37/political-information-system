$(function () {
         var table = $('#referalData').DataTable({
                processing: true,
                language:{
                  processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>'
                },
                serverSide: true,
                ordering: true,
                ajax: {
                    url: "/admin/member/dtmemberpotentialreferal",
                },
                columns:[
                    {data: 'photo', name:'photo'},
                    {data: 'name', name:'name'},
                    {data: 'totalReferal', name:'totalReferal'},
                    {data: 'address', name:'address'},
                    {data: 'contact', name:'contact'},
                    {
                        data: 'action', 
                        name:'action',
                        orderable: false,
                        searchable: false,
                        width: '15%'
                    },
                ],
                aaSorting: [[0, "desc"]],
            });
});

$(function () {
         var table = $('#inputData').DataTable({
                processing: true,
                language:{
                  processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>'
                },
                serverSide: true,
                ordering: true,
                ajax: {
                    url: "/admin/member/dtmemberpotentialinput",
                },
                columns:[
                    {data: 'photo', name:'photo'},
                    {data: 'name', name:'name'},
                    {data: 'totalInput', name:'totalInput'},
                    {data: 'address', name:'address'},
                    {data: 'contact', name:'contact'},
                    {
                        data: 'action', 
                        name:'action',
                        orderable: false,
                        searchable: false,
                        width: '15%'
                    },
                ],
                aaSorting: [[0, "desc"]],
            });
});