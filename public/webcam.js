const video = document.getElementById("video")

navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
        video.srcObject = stream
    })

function capture(){

    const canvas = document.getElementById("canvas")
    const ctx = canvas.getContext("2d")

    canvas.width = 640
    canvas.height = 480

    ctx.drawImage(video,0,0,640,480)

    canvas.toBlob(function(blob){

        const formData = new FormData()
        formData.append("image", blob, "webcam.jpg")

        fetch("/detect",{
            method:"POST",
            headers:{
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body:formData
        })
            .then(res=>res.text())
            .then(html=>{
                document.open()
                document.write(html)
                document.close()
            })

    })

}
