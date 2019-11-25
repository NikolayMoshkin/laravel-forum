window.onload = function(){
    let deleteThread = document.querySelectorAll('#deleteThread');
    for (let i=0; i < deleteThread.length; i++) {
        deleteThread[i].addEventListener('click', function (event) {
            event.preventDefault();
            // let threadId = this.dataset.threadId;
            let url = window.location.pathname;
            axios.delete(url)
                .then(function (response) {
                    console.log(response);
                    window.location.replace('/threads');
                })
        })
    }

    let deleteReply = document.querySelectorAll('.delete-reply');
    for (let i=0; i < deleteReply.length; i++) {
        deleteReply[i].addEventListener('click', function (event) {
            let confirmDelete = confirm("Удалить ответ?");
            if (confirmDelete) {
                let replyId = this.dataset.replyId;
                axios.delete('/replies/'+ replyId)
                    .then(function (response) {
                        console.log(response);
                        let elem = document.querySelector('#reply-'+ replyId);
                        elem.parentNode.removeChild(elem);
                    })
            }
        })
    }

};
