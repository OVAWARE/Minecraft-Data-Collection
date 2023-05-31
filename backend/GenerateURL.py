import os
#clone the git repo, put images in images, run program
directory = "images/"
url_prefix = "https://github.com/Jack-Bagel/Minecraft-Lora-Training/raw/main/image/5_minecraft/"

with open("urls.txt", "w") as f:
    for filename in os.listdir(directory):
        if filename.endswith(".jpg") or filename.endswith(".png"):
            url = url_prefix + filename
            f.write(url + "\n")