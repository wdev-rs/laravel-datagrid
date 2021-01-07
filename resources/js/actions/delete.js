import {h} from 'gridjs';
import Swal from "sweetalert2";

export function deleteAction(id, name) {
    return h('a', {
        href: '#',
        className: 'btn btn-sm btn-danger mx-1 datatable-delete-action',
        onClick: () => deleteItem.call(this, id, name),
    }, h('i', {className: "fas fa-trash"}));
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
