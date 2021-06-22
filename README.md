# Image API
With this API you can get images of your storage. It's based on Unsplash's API.

## GET Request
### Get images by topic
http://localhost:8000/api/photos/{topic}
(topics: animals, cars, etc.)

### Get images by topic and by width and/or height
http://localhost:8000/api/photos/{topic}?width=3840&height=2160

### Get random images with a limit
http://localhost:8000/api/photos/random?lim=5
(limit between 1-5)
<br><br>
*You can read test.sql to understand the database structure
