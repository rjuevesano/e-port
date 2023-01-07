User
- user_id (PK)
- username
- password
- type (CLIENT, SUPPLIER, ADMIN)
- status (PENDING, ACTIVE) (PENDING, APPROVED, REJECTED)
- firstname
- lastname
- mobile
- address
- avatar
- facebook_url
- portfolio_url
- about
- created
- updated

Post
- post_id (PK)
- user_id (FK)
- caption
- image_ids
- status (PENDING, PUBLISHED)
- created
- updated

Image
- image_id (PK)
- path
- created
- updated

Booking
- booking_id (PK)
- user_id_client
- user_id_supplier
- schedule_date
- status (PENDING, ACCEPTED, COMPLETED, CANCELLED)
- note
- created
- updated

Rating
- rating_id
- user_id_client
- user_id_supplier
- message
- rate
- created
- updated

Likes
- like_id (PK)
- user_id (FK)
- post_id (FK)
- created
- updated

Comment
- comment_id (PK)
- user_id (FK)
- post_id (FK)
- message
- created
- updated