import {h, html} from 'gridjs';
import Swal from "sweetalert2";

export function deleteAction(id, name) {
    return h('a', {
            href: '#',
            className: 'btn btn-sm btn-danger mx-1 datatable-delete-action',
            onClick: () => deleteItem.call(this, id, name),
        },
        html('<svg style="width: 15px; height: 15px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>'));
}

function deleteItem(id, name) {
    Swal.fire({
        title: 'Are you sure?',
        html: '<div>This action will delete ' + name.trim() + '. You won\'t be able to revert this!</div>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        animation: false
    }).then((result) => {
        if (result.value) {
            axios.delete(this.baseUrl + '/' + id)
                .then((data) => {
                    this.$refs.grid.update();
                    if (!data.data) {
                        throw Error;
                    }
                    Swal.fire({
                            title: 'Deleted!',
                            html: '<div>' + name + ' has been deleted.</div>',
                            icon: 'success',
                            animation: false
                        }
                    )
                })
                .catch(() => {
                    Swal.fire({
                            title: 'Error!',
                            html: '<div>' + name + ' delete failed.</div>',
                            icon: 'error',
                            animation: false
                        }
                    )
                });
        }
    });
}
