h1. Options

h2. thumbnail_constraint option


h3. scale (default)

scales-up the image until its width or height meets one of the boundaries

h3. stretch-best-orientation

* flips the width/height constraint to best match the image orientation,
* stretches the image to meet the constraints

h3. crop-best-orientation

* flips the width/height constraint to best match the image orientation,
* scales the image until the smaller side is within the constraints (cropping the extra)