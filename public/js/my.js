window.onload = function(){
    let likeElements = document.querySelectorAll('.mark-favourite');
    for (let i=0; i < likeElements.length; i++){
        likeElements[i].addEventListener('click', function (event) {
            event.preventDefault();
            likeElement = this;
            // let userId = this.querySelector('a').dataset.userId;
            let replyId = this.querySelector('a').dataset.replyId;
            axios.post('/replies/' + replyId + '/favourites')
                .then(function(res){
                    likeElement.querySelector('strong').innerText = res.data;
                    let thumbsUpElem  =  likeElement.querySelector('.mark-favourite a');
                    console.log(thumbsUpElem);
                    thumbsUpElem.className = thumbsUpElem.className === 'grey' ? 'blue' : 'grey';
                })
        })
    }
    let deleteThread = document.querySelectorAll('#deleteThread');
    for (let i=0; i < deleteThread.length; i++) {
        deleteThread[i].addEventListener('click', function (event) {
            event.preventDefault();
            let threadId = this.dataset.threadId;
            let url = window.location.pathname;
            axios.delete(url)
                .then(function (response) {
                    console.log(response);
                    window.location.replace('/threads');
                })
        })
    }
};
