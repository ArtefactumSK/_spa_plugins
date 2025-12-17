document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.spa-child-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {

            const childId = this.dataset.childId;
            const parentId = this.dataset.parentId;

            const childField = document.querySelector('input[name="input_child_id"]');
            const parentField = document.querySelector('input[name="input_parent_id"]');

            if (childField && parentField) {
                childField.value = childId;
                parentField.value = parentId;
            }

            document.querySelectorAll('.spa-child-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

});
