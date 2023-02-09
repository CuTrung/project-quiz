<form action="" method="post">
    <h3>Add new question</h3>
    <!-- QUESTION -->
    <span class="listQuestions">
        <span class="question d-flex gap-3">
            <div class="form-floating mb-3 w-50">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Question's 1: </label>
            </div>
            <?php addRemoveIconsQuestion(); ?>
        </span>
    </span>

    <!-- ANSWER -->
    <span class="d-flex gap-3">
        <input class="form-check-input mt-3" type="checkbox" value="" id="flexCheckChecked">
        <div class="form-floating mb-3 w-50 d-flex gap-3">
            <input type="text" class="form-control" id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Answer's 1: </label>
            <?php addRemoveIconsQuestion(); ?>
        </div>
    </span>

    <button type="submit" class="submit btn btn-primary">
        Submit
    </button>
</form>

<script>
    const fragment = document.createDocumentFragment();
    // const li = fragment
    //     .appendChild(document.createElement('section'))
    //     .appendChild(document.createElement('ul'))
    //     .appendChild(document.createElement('li'))
    //     .classList.add('text-danger')
    // li.textContent = 'hello world';
    // li.parentNode.classList.add('text-success')
    const oneLineTag = (tag, options) => {
        return Object.assign(document.createElement(tag), options);
    }

    // <span class='mt-3 d-flex gap-3'>
    //     <span class='addQuestion'>
    //         <i class='fa-solid fa-circle-plus text-success'></i>
    //     </span>
    //     <span class='removeQuestion'>
    //         <i class='fa-solid fa-trash text-danger'></i>
    //     </span>
    // </span>


    // const removeIcon = fragmentRemoveIcons
    //     .appendChild(oneLineTag('span', {
    //         className: 'removeQuestion'
    //     }))
    //     .appendChild(oneLineTag('i', {
    //         className: 'fa-solid fa-trash text-danger'
    //     }))


    const question = fragment
        .appendChild(oneLineTag('span', {
            className: 'question d-flex gap-3'
        }))
        .appendChild(oneLineTag('div', {
            className: 'form-floating mb-3 w-50'
        }))
        // .append(oneLineTag('span', {
        //     className: 'mt-3 d-flex gap-3'
        // }))
        // .appendChild(oneLineTag('span', {
        //     className: 'addQuestion'
        // }))
        // .appendChild(oneLineTag('i', {
        //     className: 'fa-solid fa-circle-plus text-success'
        // }))
        .appendChild(oneLineTag('input', {
            className: 'form-control',
            id: 'floatingInput',
            placeholder: 'name@example.com'
        }))
        .append(oneLineTag('label', {
            className: 'form-control',
            for: 'floatingInput'
        }))





    document.querySelector('.listQuestions').appendChild(fragment);
</script>

<?php
useJavaScript("
    document.querySelector('.addQuestion').onclick = () => {
        // document.querySelector('.listQuestions').appendChild(document.querySelector('.question'));
       
    }

    document.querySelector('.removeQuestion').onclick = () => {
        
    }
");
?>