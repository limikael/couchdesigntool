/* @get_score_data_map */
function(doc) {
  if (doc.type=="score")
    emit([doc._id,0], doc);

  if (doc.type=="track")
    emit([doc.score_id,1], doc);

  if (doc.type=="recording")
    emit([doc.score_id,2], doc);

  if (doc.type=="trackasset")
    emit([doc.score_id,3], doc);

  if (doc.type=="trackrecording")
    emit([doc.score_id,3], doc);  if (doc.type=="trackeffect")
    emit([doc.score_id,4], doc);
}

/* @get_user_disk_usage_map */
function(doc) {
  if (doc.type=="asset" || doc.type=="recording")
    emit(parseInt(doc.user_id), doc.filesize);
}

/* @get_user_disk_usage_reduce */
function (key, values, rereduce) {
    return sum(values);
}